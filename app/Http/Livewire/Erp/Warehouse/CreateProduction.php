<?php

namespace App\Http\Livewire\Erp\Warehouse;

use Livewire\{Component, WithPagination};
use Illuminate\Support\Facades\{Validator, Session, Auth};
use App\Models\Erp\Item\{ErpItem, ErpItemsWarehouses};
use App\Models\Erp\Warehouse\{ErpWarehouse, ErpProduction, ErpProductionContent, ErpProductionRecipe};

class CreateProduction extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    const model = 'createProduction';

    # main info #
    public $itemId;
    public $warehouseId;
    public $productionId;

    # search items #
    public $searchItem;

    # modal info #
    public $selectedArrayData = [];
    public $selectedModelData;
    public $rowId;
    public $action;

    # for control
    public $subItemId;
    public $subItem;
    public $productionAmount;

    #recipe
    public $recipeCreate = 0;
    public $recipeName = [];
    public $recipeId = null;
    public $recipeDataArray = [];
    public $recipeEditTarget;


    public function render()
    {
        $production = ErpProduction::find($this->productionId);
        if(!is_null($this->productionAmount) && is_numeric($this->productionAmount) && $this->productionAmount > 0){
            $production->amount = $this->productionAmount;
            $production->save();
        }else{
            $this->productionAmount = $production->amount;
        }
        return view('livewire.erp.warehouse.create-production',[
            'productionMainItem' => $production,
            'warehouse' => ErpWarehouse::find($this->warehouseId),
            'items' => ErpItem::with('stocks')
                ->whereIn('type', [0, 1])
                ->whereNotIn('id', [$this->itemId])
                ->whereNotIn('id', ErpProductionContent::where('production_id', $this->productionId)->pluck('item_id'))
                ->whereHas('stocks', function($q){
                    $q->where('warehouse_id', $this->warehouseId);
                    $q->where('amount','>',0);
                })
                ->where(function ($q){
                    $q->orWhere('code', 'like', '%'.$this->searchItem.'%')
                    ->orWhere('name', 'like', '%'.$this->searchItem.'%');
                })->paginate(10),
            'productionItems' => ErpProductionContent::where('production_id', $this->productionId)->get(),
            'currentRecipeItems' => ErpProductionRecipe::whereNotNull('recipe_id')->where('recipe_id',$this->recipeId)->get(),
            'recipes' => ErpProductionRecipe::whereNotNull('recipe_name')->where('item_id',$this->itemId)->with('recipeAltItems')->get(),
            'editAltRecipeItem' => ErpProductionRecipe::find($this->recipeEditTarget),
        ]);
    }

    public function process($rowId, $action, $subItemId)
    {
        $this->rowId        = $rowId;
        $this->action       = $action;
        $this->subItemId    = $subItemId;

        $this->subItem      = ErpItem::find($subItemId);

        if ($action == 'delete'){
            $this->dispatchBrowserEvent(self::model.$this->action.'modalShow');
        }elseif($action == 'insert'){
            $this->clearItem();
            $this->dispatchBrowserEvent(self::model.'upsertmodalShow');
        }elseif($action == 'update'){
            $this->selectedModelData = ErpProductionContent::find($this->rowId);
            $this->selectedArrayData = [
                'amount'    => $this->selectedModelData->amount,
                'wastage'   => $this->selectedModelData->wastage,
            ];
            $this->dispatchBrowserEvent(self::model.'upsertmodalShow');
        }elseif($action == 'addToRecipe'){
            $this->clearItem();
            $this->dispatchBrowserEvent(self::model.$this->action.'ModalShow');
        }elseif($action == 'recipeCreate'){
            $this->dispatchBrowserEvent(self::model.$this->action.'ModalShow');
        }else{
            Session::flash('error', 'Bilinmeyen bir hata oluştu.');
        }
    }

    public function upsert()
    {
        $validateData = $this->validateData();

        if($validateData['amount'] > $validateData['wastage']){

            if ($this->action == 'insert') {
                $itemAmountLimit = ErpItemsWarehouses::where('item_id',$this->rowId)->where('warehouse_id',$this->warehouseId)->where('amount','>=',$validateData['amount'])->first();
                if (is_null($itemAmountLimit)) {
                    return Session::flash('error', 'HTML ile oynama! Depo stoğundan fazla miktar girmeye de çalışma!');
                }
                ErpProductionContent::create([
                    'production_id' => $this->productionId,
                    'main_item_id'  => $this->itemId,
                    'item_id'       => $this->rowId,
                    'warehouse_id'  => $this->warehouseId,
                    'amount'        => $validateData['amount'],
                    'wastage'       => $validateData['wastage'],
                ]);
                $this->clearItem();
                Session::flash('success', trans('site.alert.data.' . $this->action . '.success'));
                $this->dispatchBrowserEvent(self::model.'upsertmodalHide');

            }elseif($this->action == 'addToRecipe'){
                $currentRecipeExistingItem = ErpProductionRecipe::whereNotNull('recipe_id')->where('recipe_id',$this->recipeId)->where('item_id',$this->subItemId)->count();
                if($currentRecipeExistingItem){
                    $this->dispatchBrowserEvent(self::model.'addToRecipeModalHide');
                    Session::flash('error', 'Seçilen ürün zaten reçetede mevcut.');
                }else{
                    $createNewRecipe = new ErpProductionRecipe;
                    $createNewRecipe->item_id = $this->subItemId;
                    $createNewRecipe->recipe_id = $this->recipeId;
                    $createNewRecipe->recipe_creator_id = Auth::user()->id;
                    $createNewRecipe->amount = $validateData['amount'];
                    $createNewRecipe->waste = $validateData['wastage'];
                    $createNewRecipe->save();
                    
                    Session::flash('success', trans('site.alert.data.insert.success'));
                    $this->dispatchBrowserEvent(self::model.'addToRecipeModalHide');
                }
                $this->clearItem();
    
            }elseif($this->action == 'update'){
                $this->selectedModelData->update($validateData);
                $this->dispatchBrowserEvent(self::model.'upsertmodalHide');
            }
        }else{
            Session::flash('error', 'Fire miktarı harcanan miktardan az olmalıdır.');
        }
    }

    public function createRecipe(){
            $createNewRecipe = new ErpProductionRecipe;
            $createNewRecipe->item_id = $this->itemId;
            $createNewRecipe->recipe_name = $this->recipeName.' - '.Auth::user()->name;
            $createNewRecipe->recipe_creator_id = Auth::user()->id;
            $createNewRecipe->save();
            $this->recipeName = [];
            $this->recipeCreate = 1;
            $this->recipeId = $createNewRecipe->id;
            $this->dispatchBrowserEvent(self::model.$this->action.'ModalHide');
            Session::flash('success', trans('site.alert.data.insert.success'));
    }

    public function addToRecipe(){
        $validateData = $this->validateData();
        if($validateData['amount'] > $validateData['wastage']){
            Session::flash('success', trans('site.alert.data.' . $this->action . '.success'));
            $this->dispatchBrowserEvent(self::model.'upsertmodalHide');
        }else{
            Session::flash('error', 'Fire miktarı harcanan miktardan az olmalıdır.');
        }
    }

    public function delete()
    {
        ErpProductionContent::find($this->rowId)->delete();
        Session::flash('success', trans('site.alert.data.' . $this->action . '.success'));
        $this->dispatchBrowserEvent(self::model.$this->action.'modalHide');
    }

    public function validateData()
    {
        $validateData = Validator::make($this->selectedArrayData, [
            'amount'      => 'required',
            'wastage'     => 'required',
        ])->validate();
        
        return $validateData;
    }

    public function showCurrentRecipeItems(){
        $this->dispatchBrowserEvent(self::model.'currentRecipeItemsModalShow');

    }
    public function currentRecipeDelete($currentRecipeId){
        ErpProductionRecipe::find($currentRecipeId)->delete();
        $this->dispatchBrowserEvent(self::model.'currentRecipeItemsModalHide');
        Session::flash('success', trans('site.alert.data.delete.success'));
    }
    
    public function recipeUseClick(){
        $this->dispatchBrowserEvent(self::model.'recipeUseModalShow');
    }
    
    public function editAltRecipeAmount($recipeAltItemId){
        $this->recipeEditTarget = $recipeAltItemId;
        $recipeAltItem = ErpProductionRecipe::find($this->recipeEditTarget);
        $this->selectedArrayData = [
            'amount'    => $recipeAltItem->amount,
            'wastage'   => $recipeAltItem->waste,
        ];
        $this->dispatchBrowserEvent(self::model.'editRecipeModalShow');
    }
    
    public function editAltRecipeAmountSubmit(){
        $validateData = $this->validateData();
        $recipeAltItem = ErpProductionRecipe::find($this->recipeEditTarget);
        $recipeAltItem->amount = $validateData['amount'];
        $recipeAltItem->waste = $validateData['wastage'];
        $recipeAltItem->save();
        $this->clearItem();
        $this->dispatchBrowserEvent(self::model.'editRecipeModalHide');
        $this->dispatchBrowserEvent(self::model.'recipeUseModalHide');

    }

    public function deleteRecipeItem($recipeAltItemId){
        ErpProductionRecipe::find($recipeAltItemId)->delete();
        $this->deleteWholeRecipe();
        $this->dispatchBrowserEvent(self::model.'recipeUseModalHide');
        Session::flash('success', trans('site.alert.data.delete.success'));
    }

    public function deleteWholeRecipe($deleteWholeRecipeId = null){
        $isDeleted = 0;
        $deleteNullContentRecipes = ErpProductionRecipe::whereNotNull('recipe_name')->whereNull('recipe_id')->get();
        foreach ($deleteNullContentRecipes as $deleteNullContentRecipe) {
            if ($deleteNullContentRecipe->recipeAltItems->count() == 0) {
                $deleteNullContentRecipe->withTrashed()->where('recipe_id',$deleteNullContentRecipe->id)->forceDelete();
                $deleteNullContentRecipe->forceDelete();
                $isDeleted++;
            }
        }
        if(is_numeric($deleteWholeRecipeId)){
            $deleteSelectedRecipe = ErpProductionRecipe::find($deleteWholeRecipeId);
            foreach($deleteSelectedRecipe->recipeAltItems as $deleteSelectedRecipeAltItems){
                $deleteSelectedRecipeAltItems->forceDelete();
            }
            $deleteSelectedRecipe->forceDelete();
            $isDeleted++;
        }

        if($isDeleted){
            $this->refresh();
        }
        $this->dispatchBrowserEvent(self::model.'recipeUseModalHide');
    }

    public function useRecipe($recipeId){
        $recipe = ErpProductionRecipe::find($recipeId);
        $productionAmount = ErpProduction::find($this->productionId)->amount;
        $warehouseItems = ErpItemsWarehouses::where('warehouse_id',$this->warehouseId)->get();
        foreach ($recipe->recipeAltItems as $recipeAltItem) {
            $warehouseItem = $warehouseItems->where('item_id',$recipeAltItem->item_id)->first();
            $isItemExist = ErpItem::find($recipeAltItem->item_id);
            if(is_null($isItemExist)){
                foreach ($warehouseItems->where('item_id',$recipeAltItem->item_id) as $value){
                    $value->delete();
                }
                ErpProductionRecipe::where('item_id',$recipeAltItem->item_id)->delete();
            }else{
                if(is_null($warehouseItem)){
                    Session::flash('error', 'Reçedeki ['.$recipeAltItem->item_id.']ID li ürün odanızda tanımlı değildir.');
                    return $this->dispatchBrowserEvent(self::model.'recipeUseModalHide');
                }
                $remaningTest = $warehouseItem->amount - ($recipeAltItem->amount * $this->productionAmount);
                if(!($remaningTest >= 0)){
                    Session::flash('error', '" '.$warehouseItem->item->name.' " adlı ürün miktarı yetersiz. Eksik miktar : '.$remaningTest.' '.$warehouseItem->item->itemToUnit->code);
                    return $this->dispatchBrowserEvent(self::model.'recipeUseModalHide');
                }
            }
        }
        $deleteRecipeOnUse = ErpProductionContent::where('production_id',$this->productionId)->get();
        foreach($deleteRecipeOnUse as $deleteRecipeOnUse_L){
            $deleteRecipeOnUse_L->delete();
        }
        $recipe2 = ErpProductionRecipe::find($recipeId);
        foreach ($recipe2->recipeAltItems as $recipeAltItem) {
            $addToUse = new ErpProductionContent;
            $addToUse->production_id = $this->productionId;
            $addToUse->main_item_id = $this->itemId;
            $addToUse->item_id = $recipeAltItem->item_id;
            $addToUse->warehouse_id = $this->warehouseId;
            $addToUse->amount = $recipeAltItem->amount * $this->productionAmount;
            $addToUse->wastage = $recipeAltItem->waste * $this->productionAmount;
            $addToUse->save();
        }
        Session::flash('success', $this->productionAmount.'" miktarı için uygulandı.');
        return $this->dispatchBrowserEvent(self::model.'recipeUseModalHide');
    }
    

    public function clearItem()
    {
        $this->selectedArrayData = [
            'amount'    => '',
            'wastage'   => '',
        ];
    }

    public function refresh(){
        return redirect(request()->header('Referer'));
    }

}
