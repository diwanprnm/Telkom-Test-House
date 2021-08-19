<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Certification;
use App\Services\Logs\LogService;
use App\Services\FileService;

use Auth;
use Session;
use Image;

// UUID
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Storage;

class CertificationController extends Controller
{
    private const ADMIN_CERTIFICATION = '/admin/certification';
    private const ADMIN_CERTIFICATION_CREATE = '/admin/certification/create';
    private const CERTIFICATION = 'CERTIFICATION';
    private const CERTIFICATION_PATH = "certification/";
    private const CREATED_AT = 'created_at';
    private const ERROR = 'error';
    private const IMAGE = 'image';
    private const IS_ACTIVE = 'is_active';
    private const MESSAGE = 'message';
    private const REQUIRED = 'required';
    private const SEARCH = 'search';
    private const TITLE = 'title';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth.admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $currentUser = Auth::user();
        $logService = new LogService();

        if (!$currentUser){ return redirect('login');}

        $message = null;
        $paginate = 10;
        $search = trim(strip_tags($request->input(self::SEARCH,'')));
        
        if ($search){
            $certifications = Certification::whereNotNull(self::CREATED_AT)
                ->where(self::TITLE,'like','%'.$search.'%')
                ->where('type',1)
                ->orderBy(self::CREATED_AT)
                ->paginate($paginate);
                //Create search log
                $logService->createLog('Search Certification', self::CERTIFICATION, json_encode(array(self::SEARCH=>$search)) );
        }else{
            $certifications = Certification::whereNotNull(self::CREATED_AT)
                ->where('type',1)
                ->orderBy(self::CREATED_AT)
                ->paginate($paginate);
        }
        
        if (count($certifications) == 0){
            $message = 'Data not found';
        }
        
        return view('admin.certification.index')
            ->with(self::MESSAGE, $message)
            ->with('data', $certifications)
            ->with(self::SEARCH, $search);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.certification.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            self::TITLE => self::REQUIRED,
            self::IS_ACTIVE => self::REQUIRED,
            self::IMAGE => 'required|mimes:jpg,jpeg,png,jfif'
        ]);

        $logService = new LogService();
        $currentUser = Auth::user();

        $certification = new Certification;
        $certification->id = Uuid::uuid4();
        $certification->title = $request->input(self::TITLE);

        if ($request->hasFile(self::IMAGE)) {

            $fileService = new FileService();
            $fileProperties = array(
                'path' => self::CERTIFICATION_PATH,
                'prefix' => "cert_",
            );
            $fileService->upload($request->file(self::IMAGE), $fileProperties);

            if(!$fileService->isUploaded()){
                return redirect(self::ADMIN_CERTIFICATION_CREATE)->with(self::ERROR, 'Save Image to directory failed');
            }
            
            $certification->image = $fileService->getFileName();
        }
        
        $certification->is_active = $request->input(self::IS_ACTIVE);
        $certification->type = 1;
        $certification->created_by = $currentUser->id;
        $certification->updated_by = $currentUser->id;
		$certification->created_at = ''.date('Y-m-d H:i:s').'';

        try{
            $certification->save();
            $logService->createLog('Create Certification', self::CERTIFICATION, $certification);

            Session::flash(self::MESSAGE, 'Certification successfully created');
            return redirect(self::ADMIN_CERTIFICATION);
        } catch(Exception $e){ return redirect(self::ADMIN_CERTIFICATION_CREATE)->with(self::ERROR, 'Save failed');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $certification = Certification::find($id);
        return view('admin.certification.edit')
            ->with('data', $certification);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            self::IMAGE => 'mimes:jpg,jpeg,png,jfif'
        ]);

        $currentUser = Auth::user();
        $certification = Certification::find($id);
        $logService = new LogService();

        $oldData = clone $certification;
        if ($request->has(self::TITLE)){
            $certification->title = $request->input(self::TITLE);
        }
        if ($request->has(self::IS_ACTIVE)){
            $certification->is_active = $request->input(self::IS_ACTIVE);
        }
        if ($request->file(self::IMAGE)) {

            $fileService = new FileService();
            $fileProperties = array(
                'path' => self::CERTIFICATION_PATH,
                'prefix' => "cert_",
                'oldFile' => $certification->image
            );
            $fileService->upload($request->file(self::IMAGE), $fileProperties);

            if(!$fileService->isUploaded()){
                return redirect(self::ADMIN_CERTIFICATION_CREATE)->with(self::ERROR, 'Save Image to directory failed');
            }

            $certification->image = $fileService->getFileName();
        }

        $certification->updated_by = $currentUser->id;
		$certification->updated_at = ''.date('Y-m-d H:i:s').'';

        try{
            $certification->save();
            $logService->createLog('Update Certification', self::CERTIFICATION, $oldData );

            Session::flash(self::MESSAGE, 'Certification successfully updated');
            return redirect(self::ADMIN_CERTIFICATION);
        } catch(Exception $e){ return redirect('/admin/certification/'.$certification->id.'edit')->with(self::ERROR, 'Save failed');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $certification = Certification::find($id);
        $logService = new LogService();

        if ($certification){
            $oldData = clone $certification;
            
            try{
                $fileService = new FileService();
                $fileProperties = array(
                    'path' => self::CERTIFICATION_PATH,
                    'fileName' => $certification->image
                );
                $fileService->deleteFile($fileProperties);

                $certification->delete();
                $logService->createLog('Delete Certification', self::CERTIFICATION, $oldData );

                Session::flash(self::MESSAGE, 'Certification successfully deleted');
                return redirect(self::ADMIN_CERTIFICATION);
            }catch (Exception $e){return redirect(self::ADMIN_CERTIFICATION)->with(self::ERROR, 'Delete failed');
            }
        }
        return redirect(self::ADMIN_CERTIFICATION)->with(self::ERROR, 'Certification not found');
    }
    
}
