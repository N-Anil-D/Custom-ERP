<div>
    <div class="card">
        {{-- üretim genel bilgileri --}}
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <label class="bg-primary p-2 text-white text-bold d-block rounded-top">Üretim genel bilgileri : {{ $productionMainItem->name }}</label>
                    <div class="table-responsive">
                        <table class="table table-responsive-md table-hover table-bordered mb-0 center">
                            <tr>
                                <td colspan="2">Üretimi yapan</td>
                                <td colspan="2">{{ Auth::user()->name }}</td>
                            </tr>
                            <tr>
                                <td colspan="2">Üretilen ürün</td>
                                <td colspan="2">{{ $productionMainItem->item->name }}</td>
                            </tr>
                            <tr>
                                <td colspan="2">Üretim yeri (oda/depo)</td>
                                <td colspan="2">{{ $warehouse->code }} / {{ $warehouse->name }}</td>
                            </tr>
                            @if($productionMainItem->status == 0)
                            <tr class="align-middle">
                                <form action="{{ route('complete.production') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="productionId" value="{{ $productionMainItem->id }}">
                                    <td>Üretilen miktar</td>
                                    <td class="center">
                                        <input name="amount" type="number" wire:model.lazy="productionAmount" class="form-control center" step="0.0001" min="0" placeholder="{{ $productionMainItem->item->itemToUnit->content }} cinsinden değeri" required>
                                    </td>
                                    <td>{{ $productionMainItem->item->itemToUnit->content }}</td>
                                    <td>
                                        <button class="btn btn-primary btn-block col-lg-12 btn-sm" type="submit">Kaydet ve üretimi sonlandır</button>
                                    </td>
                                </form>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body mt-4">
            <div class="row">
                <div class="col-12 col-xl-6">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th colspan="5" class="center">
                                        <div class="d-flex justify-content-between">
                                            <div class="d-flex align-items-center">
                                                Üretimde kullanılan ürünler
                                            </div>
                                            <div>
                                                @if ($recipeCreate == 0)
                                                    <button wire:click="process('','recipeCreate','')" type="button" class="btn btn-outline btn-primary mb-2">Reçete Oluştur</button>
                                                @else
                                                    <button wire:click="showCurrentRecipeItems" type="button" class="btn btn-outline btn-primary mb-2">Reçeteyi Görüntüle</button>
                                                @endif
                                                <button wire:click="recipeUseClick" type="button" class="btn btn-outline btn-primary mb-2 {{ $recipes->count() ? '':'disabled' }}">Reçete Kullan</button>
                                            </div>
                                        </div>
                                    </th>
                                </tr>
                                <tr>
                                    <th>#</th>
                                    <th>Ürün kodu</th>
                                    <th>Ürün adı</th>
                                    <th class="center">Kullanılan miktar</th>
                                    <th class="center">Fire</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($productionItems as $row)
                                    @if ($row->id)
                                        <tr>
                                            <td>
                                                @if($productionMainItem->status == 0)
                                                <div class="btn-group flex-wrap">
                                                    <a style="text-decoration: none;" href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
                                                        # {{ $row->id }} 
                                                        <span class="caret"></span>
                                                    </a>
                                                    <div class="dropdown-menu" role="menu">
                                                        
                                                        <button wire:click="process({{ $row->id }}, 'update', {{ $row->item->id }})" class="dropdown-item text-1">
                                                            {!! trans('site.button.update') !!}
                                                        </button>
                                                        
                                                        <button wire:click="process({{ $row->id }}, 'delete', {{ $row->item->id }})" class="dropdown-item text-1">
                                                            {!! trans('site.button.delete') !!}
                                                        </button>
                                                        
                                                    </div>
                                                </div>
                                                @endif
                                            </td>
                                            <td>{{ $row->item->code ? $row->item->code :'' }}</td>
                                            <td>{{ $row->item->name ? $row->item->name :'' }}</td>
                                            <td class="center">{{ $row->amount ? $row->amount : '' }} {{ $row->item->itemToUnit->code ? $row->item->itemToUnit->code : '' }}</td>
                                            <td class="center">{{ $row->wastage ? $row->wastage : ''}} {{ $row->item->itemToUnit->code ? $row->item->itemToUnit->code : '' }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- üretime eklenebilir ürünler --}}
                <div class="col-12 col-xl-6">
                    <div class="col-lg-12 col-sm-12 pb-sm-3 pb-md-0 mb-2">
                        <input wire:model="searchItem" type="search" class="form-control"
                            placeholder="aramak istediğiniz ürünün adını ya da diğer bilgilerini buraya yazınız.">
                    </div>
                    <div class="table-responsive">
                        <table class="table table-responsive-md table-hover table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th colspan="4" class="center">{{ $warehouse->name }} içerisinde bulunan mevcut ürünler. <br>Not: 0 stoklar görüntülenmez.</th>
                                </tr>
                                <tr>
                                    <th class="center">#</th>
                                    <th>Ürün kodu</th>
                                    <th>Ürün adı</th>
                                    <th class="center">{{ $warehouse->code }} stoğu</th>
                                    @if ($recipeCreate !== 0)
                                    <th class="center">R</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $row)
                                    @if ($row->id)
                                        <tr>
                                            <td class="center">
                                                @if($productionMainItem->status == 0)
                                                    <button wire:click="process({{ $row->id }}, 'insert', {{ $row->id }})" class="btn btn-primary btn-xs">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                @endif
                                            </td>
                                            <td>{{ $row->code }}</td>
                                            <td>{{ $row->name }}</td>
                                            <td class="center">{{ $row->stock($warehouseId)->amount }} {{ $row->itemToUnit->code }}</td>
                                            @if ($recipeCreate !== 0)
                                                <td class="center">
                                                    <button wire:click="process({{ $row->id }}, 'addToRecipe', {{ $row->id }})" class="btn btn-tertiary btn-xs">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </td>
                                            @endif
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
    
                        </table>
                    </div>
                    <br><hr>
                    {{ $items->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- modals --}}

    {{-- modal delete --}}
    <div class="modal fade" id="{{ self::model.'deletemodal' }}" tabindex="-1" aria-labelledby="{{ self::model.'deletemodalLabel' }}"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ trans('site.modal.header.'.$action) }}</h5>
                    <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Alt ürün üretimde kullanılan malzemeler arasından çıkarılacak !
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trans('site.modal.button.cancel') }}</button>
                    <button wire:click="delete" type="button" class="btn btn-primary">{{ trans('site.modal.button.confirm') }}</button>
                </div>
            </div>
        </div>
    </div>

    {{-- modal create recipe --}}
    <div class="modal fade" id="{{ self::model.'recipeCreateModal' }}" tabindex="-1" aria-labelledby="{{ self::model.'recipeCreateModalLabel' }}"
        aria-hidden="true">
        <form autocomplete="off" wire:submit.prevent="createRecipe">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ürün Reçesi Oluştur</h5>
                        <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h6 class="text-warning">İsim belirledikten sonra reçete oluşturma araçları karşınıza çıkacaktır.</h6>
                        <div class="mb-3">
                            <label class="form-label">Reçete Adı</label>
                            <input wire:model.defer="recipeName" type="text" class="form-control" placeholder="Oluşturulacak Ürün Reçetesi için isim giriniz.">
                        </div>
                    </div>
                    <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trans('site.modal.button.cancel') }}</button>
                            <button type="submit" class="btn btn-primary">{{ trans('site.modal.button.save') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- modal after create recipe watch reeeecipe items --}}
    <div class="modal fade" id="{{ self::model.'recipeItems' }}" tabindex="-1" aria-labelledby="{{ self::model.'recipeItemsLabel' }}" aria-hidden="true" wire:ignore.self>
        <form autocomplete="off" wire:submit.prevent="upsert">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ürün Reçetesi</h5>
                        <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h6 class="text-warning">DİKKAT! &nbsp;&nbsp; Reçeteyi tek bir ürün için oluşturunuz.</h6>

                        <div class="mb-3">
                            <label class="form-label">Kullanılan toplam ürün miktarı (Fire ile birlikte)</label>
                            @if ($subItem)
                            <input wire:model.defer="selectedArrayData.amount" type="number" step="0.0001" max="{{ ($subItem) ? $subItem->stock($warehouseId)->amount : '0' }}" min="0" class="form-control @error('amount') is-invalid @enderror" placeholder="{{ ($subItem) ? $subItem->itemToUnit->content : 'Birim' }} cinsinden değerini giriniz.">
                            @endif
                            @error('amount')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Toplam fire miktarı</label>
                            @if ($subItem)
                            <input wire:model.defer="selectedArrayData.wastage" type="number" step="0.0001" max="{{ ($subItem) ? $subItem->stock($warehouseId)->amount : '0' }}" min="0" class="form-control @error('wastage') is-invalid @enderror" placeholder="{{ ($subItem) ? $subItem->itemToUnit->content : 'Birim' }} cinsinden değerini giriniz.">
                            @endif
                            @error('wastage')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trans('site.modal.button.cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ trans('site.modal.button.save') }}</button>
                    </div>
                </div>
            </div>
        </form>

    </div>

    {{-- modal current recipe items --}}
    <div class="modal fade" id="{{ self::model.'currentRecipeItems' }}" tabindex="-1" aria-labelledby="{{ self::model.'currentRecipeItemsLabel' }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reçeteye Eklenmiş Ürünler</h5>
                    <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-md table-hover table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th class="center">#</th>
                                    <th>Ürün kodu</th>
                                    <th>Ürün adı</th>
                                    <th class="center">Miktar</th>
                                    <th class="center">Fire</th>
                                    <th class="center">Birim</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($currentRecipeItems as $currentRecipeItem)
                                    @if ($currentRecipeItem->id)
                                        <tr>
                                            <td class="center">
                                                <div class="btn-group flex-wrap">
                                                    <a style="text-decoration: none;" href="#" class="dropdown-toggle" data-bs-toggle="dropdown">{{ $currentRecipeItem->id }} <span class="caret"></span></a>
                                                    <div class="dropdown-menu" role="menu">
                                                        <button wire:click="currentRecipeDelete({{ $currentRecipeItem->id }})" class="dropdown-item text-1">Sil</button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $currentRecipeItem->item->code }}</td>
                                            <td>{{ $currentRecipeItem->item->name }}</td>
                                            <td class="center">{{ $currentRecipeItem->amount }}</td>
                                            <td class="center">{{ $currentRecipeItem->waste }}</td>
                                            <td class="center">{{ $currentRecipeItem->item->itemToUnit->code }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trans('site.modal.button.cancel') }}</button>
                    <button wire:click="refresh" type="button" class="btn btn-primary">Bitir</button>
                </div>
            </div>
        </div>
    </div>

    {{-- modal use recipe --}}
    <div class="modal fade" id="{{ self::model.'recipeUseModal' }}" tabindex="-1" aria-labelledby="{{ self::model.'recipeUseModalLabel' }}"
        aria-hidden="true">
        <div class="modal-dialog modal-block modal-block-lg"> {{-- LARGE MODAL --}}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reçeteler</h5>
                    <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6 class="text-warning">DİKKAT! &nbsp;&nbsp; Kullandığınız reçetedeki ürünlerden <span class="text-sm">(x {{ $productionMainItem->amount }})</span> miktarda kullanılacaktır.</h6>

                    @foreach ($recipes as $recipe)
                        <div class="accordion" id="accordion">
                            <div class="card card-default">
                                <div class="card-header">
                                    <h4 class="card-title pr-4 m-0">
                                        <a class="accordion-toggle accordionManuel d-flex justify-content-between" data-toggle="collapse" data-parent="#accordion" href="#collapse{{ $recipe->id }}">
                                            {{ $loop->iteration .') '. $recipe->recipe_name }}
                                            <div class="my-auto">
                                                <button wire:click="useRecipe({{ $recipe->id }})" class="btn btn-success btn-xs px-3 mx-1">Kullan</button>
                                                @if ($recipe->recipe_creator_id == Auth::user()->id)
                                                    <button wire:click="deleteWholeRecipe({{ $recipe->id }})" class="btn btn-secondary btn-xs mx-1">Sil</button>
                                                @endif
                                            </div>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse{{ $recipe->id }}" class="collapse" style="{{ $recipes->count() == 1 ? 'display:block':''}}">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-responsive-md table-hover table-bordered mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Ürün kodu</th>
                                                        <th>Ürün adı</th>
                                                        <th class="center">Miktar</th>
                                                        <th class="center">Fire</th>
                                                        <th class="center">Birim</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($recipe->recipeAltItems as $recipeAltItem)
                                                        @if ($recipeAltItem->item)
                                                            <tr>
                                                                <td class="center">
                                                                    <div class="btn-group flex-wrap">
                                                                        <a style="text-decoration: none;" href="#" class="dropdown-toggle" data-bs-toggle="dropdown">#<span class="caret"></span></a>
                                                                        <div class="dropdown-menu" role="menu">
                                                                            @if ($recipe->recipe_creator_id == Auth::user()->id)
                                                                                <button wire:click="deleteRecipeItem({{ $recipeAltItem->id }})" class="dropdown-item text-1">Sil</button>
                                                                                <button wire:click="editAltRecipeAmount({{ $recipeAltItem->id }})" class="dropdown-item text-1">Düzenle</button>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>{{ $recipeAltItem->item->code }}</td>
                                                                <td>{{ $recipeAltItem->item->name }}</td>
                                                                <td>{{ $recipeAltItem->amount }}</td>
                                                                <td>{{ $recipeAltItem->waste }}</td>
                                                                <td>{{ $recipeAltItem->item->itemToUnit->code }}</td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trans('site.modal.button.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>

    {{-- modal edit existing recipe --}}
    <div class="modal fade" id="{{ self::model.'editRecipeItems' }}" tabindex="-1" aria-labelledby="{{ self::model.'editRecipeItemsLabel' }}" aria-hidden="true" wire:ignore.self>
        <form autocomplete="off" wire:submit.prevent="editAltRecipeAmountSubmit">
            <div class="modal-dialog">
                <div class="modal-content">
                    @if($editAltRecipeItem)
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $editAltRecipeItem->item->name }}</h5>
                            <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Kullanılan toplam ürün miktarı (Fire ile birlikte)</label>
                                <input wire:model.defer="selectedArrayData.amount" type="number" min="0" max="{{ ($editAltRecipeItem->item) ? $editAltRecipeItem->item->stock($warehouseId)->amount : '0' }}" class="form-control @error('amount') is-invalid @enderror" placeholder="{{ ($editAltRecipeItem->item) ? $editAltRecipeItem->item->itemToUnit->content : 'Birim' }} cinsinden değerini giriniz.">
                                @error('amount')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Toplam fire miktarı</label>
                                <input wire:model.defer="selectedArrayData.wastage" type="number" min="0" max="{{ ($editAltRecipeItem->item) ? $editAltRecipeItem->item->stock($warehouseId)->amount : '0' }}" class="form-control @error('wastage') is-invalid @enderror" placeholder="{{ ($editAltRecipeItem->item) ? $editAltRecipeItem->item->itemToUnit->content : 'Birim' }} cinsinden değerini giriniz.">
                                @error('wastage')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    @endif
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trans('site.modal.button.cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ trans('site.modal.button.save') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
    {{-- modal insert or update --}}
    <div class="modal fade" id="{{ self::model.'upsertmodal' }}" tabindex="-1" aria-labelledby="{{ self::model.'upsertmodalLabel' }}"
        aria-hidden="true" wire:ignore.self>
        <form autocomplete="off" wire:submit.prevent="upsert">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ trans('site.modal.header.'.$action) }}</h5>
                        <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">

                        <div class="mb-3">
                            <label class="form-label">Kullanılan toplam ürün miktarı (Fire ile birlikte)</label>
                            @if ($subItem)
                            <input wire:model.defer="selectedArrayData.amount" type="number" step="0.0001" max="{{ ($subItem) ? $subItem->stock($warehouseId)->amount : '0' }}" min="0" class="form-control @error('amount') is-invalid @enderror" placeholder="{{ ($subItem) ? $subItem->itemToUnit->content : 'Birim' }} cinsinden değerini giriniz.">
                            @endif
                            @error('amount')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Toplam fire miktarı</label>
                            @if ($subItem)
                            <input wire:model.defer="selectedArrayData.wastage" type="number" step="0.0001" max="{{ ($subItem) ? $subItem->stock($warehouseId)->amount : '0' }}" min="0" class="form-control @error('wastage') is-invalid @enderror" placeholder="{{ ($subItem) ? $subItem->itemToUnit->content : 'Birim' }} cinsinden değerini giriniz.">
                            @endif
                            @error('wastage')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trans('site.modal.button.cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ trans('site.modal.button.save') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>    
    
        window.addEventListener('{{ self::model }}deletemodalShow', event => {
            $('#{{ self::model }}deletemodal').modal('show');
        });
        window.addEventListener('{{ self::model }}deletemodalHide', event => {
            $('#{{ self::model }}deletemodal').modal('hide');
        });

        window.addEventListener('{{ self::model }}recipeCreateModalShow', event => {
            $('#{{ self::model }}recipeCreateModal').modal('show');
        });
        window.addEventListener('{{ self::model }}recipeCreateModalHide', event => {
            $('#{{ self::model }}recipeCreateModal').modal('hide');
        });

        window.addEventListener('{{ self::model }}recipeUseModalShow', event => {
            $('#{{ self::model }}recipeUseModal').modal('show');
        });
        window.addEventListener('{{ self::model }}recipeUseModalHide', event => {
            $('#{{ self::model }}recipeUseModal').modal('hide');
        });

        window.addEventListener('{{ self::model }}addToRecipeModalShow', event => {
            $('#{{ self::model }}recipeItems').modal('show');
        });
        window.addEventListener('{{ self::model }}addToRecipeModalHide', event => {
            $('#{{ self::model }}recipeItems').modal('hide');
        });

        window.addEventListener('{{ self::model }}editRecipeModalShow', event => {
            $('#{{ self::model }}editRecipeItems').modal('show');
        });
        window.addEventListener('{{ self::model }}editRecipeModalHide', event => {
            $('#{{ self::model }}editRecipeItems').modal('hide');
        });

        window.addEventListener('{{ self::model }}currentRecipeItemsModalShow', event => {
            $('#{{ self::model }}currentRecipeItems').modal('show');
        });
        window.addEventListener('{{ self::model }}currentRecipeItemsModalHide', event => {
            $('#{{ self::model }}currentRecipeItems').modal('hide');
        });

        window.addEventListener('{{ self::model }}upsertmodalShow', event => {
            $('#{{ self::model }}upsertmodal').modal('show');
        });
        window.addEventListener('{{ self::model }}upsertmodalHide', event => {
            $('#{{ self::model }}upsertmodal').modal('hide');
        });
        
        $(".accordionManuel").click(function() {
            $($(this)[0]['hash']).toggle("slide", { direction: "up" }, 600);
        });
    </script>
    
    @include('parts.alert')
    @include('parts.alertify')

</div>


