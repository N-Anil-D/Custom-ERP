<?php

namespace App\Http\Livewire\Erp\Warehouse;

use Illuminate\Support\Facades\{Auth, Validator, Session};
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\{Component, WithPagination, WithFileUploads};
use App\Models\Erp\Warehouse\ErpWorkOrder;

class WorkOrder extends Component
{
    use WithPagination, WithFileUploads;
    
    protected $paginationTheme = 'bootstrap';
    
    const model = 'WorkOrder';

    public $search;
    public $newWorkOrder=[];
    public $editWorkOrder=[];
    public $edit;
    public $fileName;
    public $fileExtenison;

    
    public function render()
    {
        return view('livewire.erp.warehouse.work-order',[
            'workOrders' => ErpWorkOrder::paginate(20),
        ]);
    }

    public function clearModal(){
        
        $this->newWorkOrder = [
            'file'=>null,
            'name'=>''
        ];
        
    }

    public function openModal($mode,$workOrderID=null){
        if($mode == 'new'){
            $this->clearModal();
            $this->dispatchBrowserEvent(self::model.'NewShow');
        }else if($mode == 'edit'){
            $this->editWorkOrder = [
                'existWorkOrderId'=>null,
                'file'=>null,
                'name'=>''
            ];
            $this->edit = ErpWorkOrder::find($workOrderID);
            $this->editWorkOrder['existWorkOrderId'] = $workOrderID;
            $this->editWorkOrder['name'] = $this->edit->name;

            $this->dispatchBrowserEvent(self::model.'EditShow');
        }
    }

    public function createNewWorkOrder(){
        Validator::validate($this->newWorkOrder, [
            'file' =>'mimes:xlsx,xls|max:1024'
        ]);
        //dosyayı sisteme yükler ve adını değişkenlere atar
        $this->saveFileToSystem($this->newWorkOrder['file']);
        //dosyayı sisteme yükler ve adını değişkenlere atar
        
        if($this->newWorkOrder['name'] == '' || $this->newWorkOrder['name'] == null){
            $name = $this->fileName;
        }else{
            $name = $this->newWorkOrder['name'];
        }
        $newWorkOrderRecord = new ErpWorkOrder;
        $newWorkOrderRecord->name = $name;
        $newWorkOrderRecord->work_order_path = 'app/isEmri/'.$this->fileName.".".$this->fileExtension;
        $newWorkOrderRecord->user_id = Auth::user()->id;
        $newWorkOrderRecord->save();

        $this->clearModal();
        $this->dispatchBrowserEvent(self::model.'NewHide');
        Session::flash('success','İş emri başarılı bir şekilde yüklendi.');
    }

    public function downloadFile($id){
        $file = ErpWorkOrder::find($id);
        if(isset($file)){
            $pathName = explode('.',$file->work_order_path);
            $extension = end($pathName);
            return response()->download(storage_path($file->work_order_path),$file->name.'.'.$extension,['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);
        }else{
            return Session::flash('error','Bilinmeyen bir hata oluştu tekrar deneyiniz.');
        }
    }

    public function editWorkOrder(){
        Validator::validate($this->editWorkOrder, [
            'file' =>'mimes:xlsx,xls|max:1024'
        ]);
        $this->edit = ErpWorkOrder::find($this->editWorkOrder['existWorkOrderId']);
        
        //dosyayı sisteme yükler ve adını değişkenlere atar
        $this->saveFileToSystem($this->editWorkOrder['file']);
        //dosyayı sisteme yükler ve adını değişkenlere atar
        unlink(storage_path($this->edit->work_order_path));
        if($this->editWorkOrder['name'] == '' || $this->editWorkOrder['name'] == null){
            $name = $this->fileName;
        }else{
            $name = $this->editWorkOrder['name'];
        }
        $this->edit->name = $name;
        $this->edit->work_order_path = 'app/isEmri/'.$this->fileName.".".$this->fileExtension;
        $this->edit->save();
        $this->dispatchBrowserEvent(self::model.'EditHide');
        Session::flash('success','İş emri başarılı bir şekilde güncellendi.');

    }

    public function saveFileToSystem($file){
        $fileNameWithoutExtension = pathinfo($file->getClientOriginalName(),PATHINFO_FILENAME);
        $this->fileName = Str::slug($fileNameWithoutExtension,'-');
        $this->fileExtension = $file->getClientOriginalExtension();
        Storage::putFileAs('isEmri', $file, $this->fileName.".".$this->fileExtension);

    }

}
