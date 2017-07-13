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
            $search = trim($request->input('search'));
            $select = array(
                "backup_history.id","backup_history.file","backup_history.created_at","users.name"
            );
            if ($search != null){
            
                $dataBackups = BackupHistory::select($select)->whereNotNull('backup_history.created_at')
                ->join("users","users.id","=","backup_history.user_id")
                    ->where('action','like','%'.$search.'%')
                    ->orderBy('backup_history.created_at', 'desc')
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
                $dataBackups = BackupHistory::select($select)->whereNotNull('backup_history.created_at')
                    ->join("users","users.id","=","backup_history.user_id")
                    ->orderBy('backup_history.created_at', 'desc')
                    ->paginate($paginate);
            }
            
            if (count($dataBackups) == 0){
                $message = 'Data not found';
            }
            
            return view('admin.backup.index')
                ->with('message', $message)
                ->with('data', $dataBackups)
                ->with('search', $search);
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
                Session::flash('message', 'Backup successfully created');
                return redirect('/admin/backup');
            } catch(Exception $e) {
                Session::flash('error', 'Backup failed');
                return redirect('/admin/backup');
            }
        }else{
            Session::flash('error', 'Access Denied');
            return redirect('/admin/backup');
        }
    }

    public function restore(Request $request)
    {
        $currentUser = Auth::user();
        $backups = BackupHistory::find($request->input('id')); 
        if($backups){
            try {
                Artisan::call('db:restore '.$backups->file);  
                Session::flash('message', 'Restore successfully created');
                return redirect('/admin/backup');
            } catch(Exception $e) {
                Session::flash('error', 'Restore failed');
                return redirect('/admin/backup');
            }
        }else{
            Session::flash('error', 'Data Not Found');
            return redirect('/admin/backup');
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
                Session::flash('message', 'Backup successfully deleted');
                return redirect('/admin/backup');
            }catch (Exception $e){
                Session::flash('error', 'Delete failed');
                return redirect('/admin/backup');
            }
        }
    }
}
