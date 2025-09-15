<?php

namespace App\Livewire;

use App\Models\Account;
use App\Models\Category;
use App\Models\Client;
use App\Models\Contract;
use App\Models\ContractPartner;
use App\Models\Delivery;
use App\Models\DetailContract;
use App\Models\DetailItem;
use App\Models\Item;
use App\Models\Partner;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('')]
class History extends Component
{
    // public $list = [];

    public function mount(){
        if(!Gate::allows('history-permission',3))
            abort('404');
    }

    public function updated(){
        dd("adf");
    }

    public function valideWithPassword($password)
    {
        if(!Gate::allows('transaction-permission',3))
            abort('404');
        // Valida la contraseña (ejemplo: contra la del usuario actual)
        if (!Hash::check($password, Auth::user()->password)) {
            return ['success' => false, 'message' => 'Contraseña incorrecta'];
        }
        return ['success' => true];
    }

    // ---------------------------
    // Users
    // ---------------------------
    public function linkedDataUser($id)
    {
        $user = User::withTrashed()->find($id);
        if (!$user) return [];

        return [
            'permissions' => DB::table('user_permissions')->where('user_id', $id)->get(['id'])->toArray(),
            // agrega otras relaciones si existen
        ];
    }

    // ---------------------------
    // Clients
    // ---------------------------
    public function linkedDataClient($id)
    {
        $client = Client::withTrashed()->find($id);
        if (!$client) return [];

        return [
            'contracts' => Contract::withTrashed()->where('client_id', $id)->get(['id'])->toArray(),
        ];
    }

    // ---------------------------
    // Contracts
    // ---------------------------
    public function linkedDataContract($id)
    {
        $contract = Contract::withTrashed()->find($id);
        if (!$contract) return [];

        return [
            'detail_contracts' => DetailContract::withTrashed()->where('contract_id', $id)->get(['id'])->toArray(),
            'deliveries' => Delivery::withTrashed()->where('contract_id', $id)->get(['id'])->toArray(),
            'contract_partners' => ContractPartner::withTrashed()->where('contract_id', $id)->get(['id'])->toArray(),
            'transactions' => Transaction::withTrashed()->where('contract_id', $id)->get(['id'])->toArray(),
        ];
    }

    // ---------------------------
    // Contract Partners
    // ---------------------------
    public function linkedDataContractPartner($id)
    {
        $cp = ContractPartner::withTrashed()->find($id);
        if (!$cp) return [];

        return [
            'transactions' => Transaction::withTrashed()->where('contract_partner_id', $id)->get(['id'])->toArray(),
        ];
    }

    // ---------------------------
    // Detail Contracts
    // ---------------------------
    public function linkedDataDetailContract($id)
    {
        $detail = DetailContract::withTrashed()->find($id);
        if (!$detail) return [];

        return [
            'delivery_detail_contracts' => DB::table('delivery_detail_contracts')->where('detail_contract_id', $id)->get(['id'])->toArray(),
        ];
    }

    // ---------------------------
    // Detail Items
    // ---------------------------
    public function linkedDataDetailItem($id)
    {
        $detailItem = DetailItem::withTrashed()->find($id);
        if (!$detailItem) return [];

        return []; // no tiene dependencias en cascada fuera de sí mismo
    }

    // ---------------------------
    // Items
    // ---------------------------
    public function linkedDataAssembly($id)
    {
        $item = Item::withTrashed()->find($id);
        if (!$item) return [];

        return [
            'detail_items' => DetailItem::withTrashed()->where('item_id', $id)->get(['id'])->toArray(),
        ];
    }

    // ---------------------------
    // Products
    // ---------------------------
    public function linkedDataProduct($id)
    {
        $product = Product::withTrashed()->find($id);
        if (!$product) return [];

        return [
            'detail_items' => DetailItem::withTrashed()->where('product_id', $id)->get(['id'])->toArray(),
        ];
    }

    // ---------------------------
    // Suppliers
    // ---------------------------
    public function linkedDataSupplier($id)
    {
        $supplier = Supplier::withTrashed()->find($id);
        if (!$supplier) return [];

        return [
            'products' => Product::withTrashed()->where('supplier_id', $id)->get(['id'])->toArray(),
        ];
    }

    // ---------------------------
    // Partners
    // ---------------------------
    public function linkedDataPartner($id)
    {
        $partner = Partner::withTrashed()->find($id);
        if (!$partner) return [];

        return [
            'contract_partners' => ContractPartner::withTrashed()->where('partner_id', $id)->get(['id'])->toArray(),
        ];
    }

    // ---------------------------
    // Categories
    // ---------------------------
    public function linkedDataCategory($id)
    {
        $category = Category::withTrashed()->find($id);
        if (!$category) return [];

        return [
            'items' => Item::withTrashed()->where('category_id', $id)->get(['id'])->toArray(),
            'products' => Product::withTrashed()->where('category_id', $id)->get(['id'])->toArray(),
        ];
    }

    // ---------------------------
    // Accounts
    // ---------------------------
    public function linkedDataAccount($id)
    {
        $account = Account::withTrashed()->find($id);
        if (!$account) return [];

        return [
            'transactions' => Transaction::withTrashed()->where('account_id', $id)->get(['id'])->toArray(),
        ];
    }

    // ---------------------------
    // Transactions
    // ---------------------------
    public function linkedDataTransaction($id)
    {
        $transaction = Transaction::withTrashed()->find($id);
        if (!$transaction) return [];

        return []; // no hay eliminaciones en cascada (SET NULL)
    }


    // 🔹 Users
    public function restoreUser($id)
    {
        $user = User::withTrashed()->find($id);
        $user?->restore();
    }

    public function removeUser($id)
    {
        $user = User::withTrashed()->find($id);
        $user?->forceDelete();
    }

    // 🔹 Transactions
    public function restoreTransaction($id)
    {
        $transaction = Transaction::withTrashed()->find($id);
        $transaction?->restore();
    }

    public function removeTransaction($id)
    {
        $transaction = Transaction::withTrashed()->find($id);
        $transaction?->forceDelete();
    }

    // 🔹 Products
    public function restoreProduct($id)
    {
        $product = Product::withTrashed()->find($id);
        $product?->restore();
    }

    public function removeProduct($id)
    {
        $product = Product::withTrashed()->find($id);
        $product?->forceDelete();
    }

    // 🔹 Partners
    public function restorePartner($id)
    {
        $partner = Partner::withTrashed()->find($id);
        $partner?->restore();
    }

    public function removePartner($id)
    {
        $partner = Partner::withTrashed()->find($id);
        $partner?->forceDelete();
    }

    // 🔹 Clients
    public function restoreClient($id)
    {
        $client = Client::withTrashed()->find($id);
        $client?->restore();
    }

    public function removeClient($id)
    {
        $client = Client::withTrashed()->find($id);
        $client?->forceDelete();
    }

    // 🔹 Contracts
    public function restoreContract($id)
    {
        $contract = Contract::withTrashed()->find($id);
        $contract?->restore();
    }

    public function removeContract($id)
    {
        $contract = Contract::withTrashed()->find($id);
        $contract?->forceDelete();
    }

    // 🔹 Detail Contracts
    public function restoreDetailContract($id)
    {
        $detail_contract = DetailContract::withTrashed()->find($id);
        $detail_contract?->restore();
    }

    public function removeDetailContract($id)
    {
        $detail_contract = DetailContract::withTrashed()->find($id);
        $detail_contract?->forceDelete();
    }

    // 🔹 Item Details
    public function restoreDetailItem($id)
    {
        $item_detail = DetailItem::withTrashed()->find($id);
        $item_detail?->restore();
    }

    public function removeDetailItem($id)
    {
        $item_detail = DetailItem::withTrashed()->find($id);
        $item_detail?->forceDelete();
    }

    // 🔹 Categories
    public function restoreCategory($id)
    {
        $category = Category::withTrashed()->find($id);
        $category?->restore();
    }

    public function removeCategory($id)
    {
        $category = Category::withTrashed()->find($id);
        $category?->forceDelete();
    }

    // 🔹 Accounts
    public function restoreAccount($id)
    {
        $account = Account::withTrashed()->find($id);
        $account?->restore();
    }

    public function removeAccount($id)
    {
        $account = Account::withTrashed()->find($id);
        $account?->forceDelete();
    }

    // 🔹 Suppliers
    public function restoreSupplier($id)
    {
        $supplier = Supplier::withTrashed()->find($id);
        $supplier?->restore();
    }

    public function removeSupplier($id)
    {
        $supplier = Supplier::withTrashed()->find($id);
        $supplier?->forceDelete();
    }

    // 🔹 Contract Partners
    public function restoreContractPartner($id)
    {
        $contract_partner = ContractPartner::withTrashed()->find($id);
        $contract_partner?->restore();
    }

    public function removeContractPartner($id)
    {
        $contract_partner = ContractPartner::withTrashed()->find($id);
        $contract_partner?->forceDelete();
    }

    // 🔹 Assemblies (Items)
    public function restoreAssembly($id)
    {
        $item = Item::withTrashed()->find($id);
        $item?->restore();
    }

    public function removeItem($id)
    {
        $item = Item::withTrashed()->find($id);
        $item?->forceDelete();
    }


    public function render()
    {
        if(!Gate::allows('history-permission',3))
            abort('404');
        $list = [
            'users'             => User::onlyTrashed()->get(),
            'transactions'      => Transaction::onlyTrashed()->get(),
            'products'          => Product::onlyTrashed()->get(),
            'partners'          => Partner::onlyTrashed()->get(),
            'clients'            => Client::onlyTrashed()->get(),
            'contracts'         => Contract::onlyTrashed()->get(),
            'detail_contracts'  => DetailContract::onlyTrashed()->get(),
            'detail_items'      => DetailItem::onlyTrashed()->get(),
            'categories'        => Category::onlyTrashed()->get(),
            'accounts'          => Account::onlyTrashed()->get(),
            'suppliers'         => Supplier::onlyTrashed()->get(),
            'contract_partners' => ContractPartner::onlyTrashed()->get(),
            'assemblies'        => Item::onlyTrashed()->get(),
        ];
        $heads = ['Fecha','ID','Tipo','Codigo','NIT','CI','Nombre','Descripción','Monto'];
        return view('livewire.history',compact(['heads','list']));
    }
}
