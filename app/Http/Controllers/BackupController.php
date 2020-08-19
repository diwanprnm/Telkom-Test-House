<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Jobs\ChangeLocale;

use App\User;
use App\BackupHistory;
use App\Services\Logs\LogService;

use Auth;
use Response;
use Artisan;
use Session;
use Storage;

use File;

class BackupController extends Controller
{
    private const ADMIN_BACKUP = '/admin/backup';
    private const BACKUP_CREATED_AT = 'backup_history.created_at';
    private const BACKUP_DATA = 'backup-data/';
    private const ERROR = 'error';
    private const MESSAGE = 'message';
    private const MINIO = 'minio';
    private const SEARCH = 'search';
    
    public function __construct()
    {
        $this->middleware('auth.admin');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $logService = new LogService();
        $message = null;
        $paginate = 10;
        $search = trim(strip_tags($request->input(self::SEARCH,'')));
        
        $select = array(
            'backup_history.id',
            'backup_history.file',
            self::BACKUP_CREATED_AT,
            'users.name'
        );
        $dataBackups = BackupHistory::select($select)->whereNotNull(self::BACKUP_CREATED_AT)
            ->join("users","users.id","=","backup_history.user_id");

        if ($search){
            $dataBackups = $dataBackups->where('file','like','%'.$search.'%');
            $logService->createLog('Search Backup File', 'BACKUP & RESTORE', json_encode(array(self::SEARCH=>$search)) );
        }

        $dataBackups = $dataBackups->orderBy(self::BACKUP_CREATED_AT, 'desc')
                    ->paginate($paginate);
        
        if (count($dataBackups) == 0){
            $message = 'Data not found';
        }
        
        return view('admin.backup.index')
            ->with(self::MESSAGE, $message)
            ->with('data', $dataBackups)
            ->with(self::SEARCH, $search);
        
    } 

     /**
     * Backup mysql into sql file.
     * Make sure mysql client is installed in current container
     */
    public function backup(Request $request)
    {
        $currentUser = Auth::user();
        $backup = new BackupHistory();

        try {
            Artisan::call('db:backup');
            $output = Artisan::output();

            $backup->user_id = $currentUser->id;
            $backup->created_by = $currentUser->id;
            $backup->restore_by = '';
            $backup->updated_by = '';
            $backup->file = explode(" ",$output)[4];
            $backup->is_active = 1;
            $backup->save();

            $backupFile = Storage::disk('tmp')->get($backup->file);
            Storage::disk(self::MINIO)->put(self::BACKUP_DATA.$backup->file, $backupFile );
            File::delete(storage_path('/tmp/'.$backup->file));

            Session::flash(self::MESSAGE, 'Backup successfully created');
            return redirect(self::ADMIN_BACKUP);
        } catch(Exception $e) {
            return redirect(self::ADMIN_BACKUP)->with(self::ERROR, 'Backup failed');
        }
    }

    // this just triger backup list, real restore will break tracker.
    public function restore(Request $request,$id)
    {
        $backups = BackupHistory::find($id); 
        if($backups){
            try {
                $file = Storage::disk(self::MINIO)->get(self::BACKUP_DATA.$backups->file);
                Storage::disk('tmp')->put($backups->file,$file);
                /**
                 * This just triger backup list
                 * Artisan::call('db:restore '.$backups->file) <<< will restore but break tracker
                 */
                Artisan::call('db:restore');
                File::delete(storage_path('tmp/'.$backups->file));

                Session::flash(self::MESSAGE, 'Restore successfully created');
                return redirect(self::ADMIN_BACKUP);
            } catch(Exception $e) {
                return redirect(self::ADMIN_BACKUP)->with(self::ERROR, 'Restore failed');
            }
        }else{
            Session::flash(self::ERROR, 'Data Not Found');
            return redirect(self::ADMIN_BACKUP);
        }
    }


    public function destroy($id)
    {
        $backupSql = BackupHistory::find($id); 
        if ($backupSql ){
            try{
                BackupHistory::where('id', '=' ,''.$id.'')->delete(); 
                Storage::disk(self::MINIO)->delete(self::BACKUP_DATA.$backupSql->file);
                Session::flash(self::MESSAGE, 'Backup successfully deleted');
                return redirect(self::ADMIN_BACKUP);
            }catch (Exception $e){
                return redirect(self::ADMIN_BACKUP)->with(self::ERROR, 'Delete failed');
            }
        }
    }

    public function viewMedia($id)
    {
        $sql = BackupHistory::where("id",$id)->first();

        if (!$sql){
            Session::flash(self::MESSAGE, 'Data not found');
            return redirect(self::ADMIN_BACKUP);
        }

        $file = Storage::disk(self::MINIO)->get(self::BACKUP_DATA.$sql->file);
        $headers = [
            'Content-Type' => 'text/sql',
            'Content-Description' => 'File Transfer',
            'Content-Disposition' => "attachment; filename={$sql->file}",
            'filename'=> $sql->file
        ];
        return response($file, 200, $headers);
    }
}
