<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Jobs\ChangeLocale;

use App\Device;
use App\Examination;
use App\ExaminationType;
use App\ExaminationAttach;
use App\ExaminationLab;
use App\User;
use App\Logs;
use App\BackupHistory;

use Auth;
use Response;
use Artisan;
use Session;

use File;

class BackupController extends Controller
{
    private const SEARCH = 'search';
    private const BACKUP_CREATED_AT = 'backup_history.created_at';
    private const MESSAGE = 'message';
    private const ADMIN_BACKUP = '/admin/backup';
    private const ERROR = 'error';
    
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
        $currentUser = Auth::user(); 

        if ($currentUser){
            $message = null;
            $paginate = 10;
            $search = trim($request->input(self::SEARCH));
            $select = array(
                'backup_history.id','backup_history.file',self::BACKUP_CREATED_AT,'users.name'
            );
            if ($search != null){
            
                $dataBackups = BackupHistory::select($select)->whereNotNull(self::BACKUP_CREATED_AT)
                ->join("users","users.id","=","backup_history.user_id")
                    ->where('action','like','%'.$search.'%')
                    ->orderBy(self::BACKUP_CREATED_AT, 'desc')
                    ->paginate($paginate);

                $backups = new BackupHistory;
                $backups->user_id = $currentUser->id;
                $backups->action = "Search Backup File"; 
                $dataSearch = array("search"=>$search);
                $backups->data = json_encode($dataSearch);
                $backups->created_by = $currentUser->id;
                $backups->page = "BACKUP & RESTORE";
                $backups->save();

            }else{
                $dataBackups = BackupHistory::select($select)->whereNotNull(self::BACKUP_CREATED_AT)
                    ->join("users","users.id","=","backup_history.user_id")
                    ->orderBy(self::BACKUP_CREATED_AT, 'desc')
                    ->paginate($paginate);
            }
            
            if (count($dataBackups) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.backup.index')
                ->with(self::MESSAGE, $message)
                ->with('data', $dataBackups)
                ->with(self::SEARCH, $search);
        }
    } 

    public function backup(Request $request)
    {
        $currentUser = Auth::user();
        $backup = new BackupHistory();

        if($currentUser){
            $backup->user_id = $currentUser->id;
            $backup->created_by = $currentUser->id;
            $backup->file = date('YmdHis').".sql";
            $backup->is_active = 1;
             try {
                Artisan::call('db:backup'); 
                $backup->save(); 
                Session::flash(self::MESSAGE, 'Backup successfully created');
                return redirect(self::ADMIN_BACKUP);
            } catch(Exception $e) {
                Session::flash(self::ERROR, 'Backup failed');
                return redirect(self::ADMIN_BACKUP);
            }
        }else{
            Session::flash(self::ERROR, 'Access Denied');
            return redirect(self::ADMIN_BACKUP);
        }
    }

    public function restore(Request $request)
    {
        $backups = BackupHistory::find($request->input('id')); 
        if($backups){
            try {
                Artisan::call('db:restore '.$backups->file);  
                Session::flash(self::MESSAGE, 'Restore successfully created');
                return redirect(self::ADMIN_BACKUP);
            } catch(Exception $e) {
                Session::flash(self::ERROR, 'Restore failed');
                return redirect(self::ADMIN_BACKUP);
            }
        }else{
            Session::flash(self::ERROR, 'Data Not Found');
            return redirect(self::ADMIN_BACKUP);
        }
      
    } 
    public function destroy($id)
    {
        
        $exam = BackupHistory::find($id); 
        if ($exam ){
            try{
                BackupHistory::where('id', '=' ,''.$id.'')->delete(); 
                
                if (File::exists(storage_path().'\app\\public\\backup-data\\'.$exam->file)){
                    File::deleteDirectory(storage_path().'\app\\public\\backup-data\\'.$exam->file);
                }
                Session::flash(self::MESSAGE, 'Backup successfully deleted');
                return redirect(self::ADMIN_BACKUP);
            }catch (Exception $e){
                Session::flash(self::ERROR, 'Delete failed');
                return redirect(self::ADMIN_BACKUP);
            }
        }
    }
}
