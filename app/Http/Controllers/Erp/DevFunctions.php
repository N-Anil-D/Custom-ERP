<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Erp\Item\{ErpItem,ErpItemsWarehouses};
use App\Models\Erp\Warehouse\ErpProductionRecipe;
use App\Models\Erp\Warehouse\ErpFinishedProduct;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\{ErpItemEkle,ErpWarehouseItems,ErpItemLocationImport,ErpFinishedProducts};

class DevFunctions extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('developer.access');
    }

    public function importErpItems(Request $request){
        Validator::validate($request->all(), [
            'importFile' => [ 'required','mimes:xlsx,xls']
        ]);
        Excel::import(new ErpItemEkle, $request->importFile);
        dd('Ürünler Eklendi.');
    }

    public function importErpItemsToWarehouse(Request $request){
        Validator::validate($request->all(), [
            'importFile' => [ 'required','mimes:xlsx,xls']
        ]);
        Excel::import(new ErpWarehouseItems, $request->importFile);
        dd('Ürünler Eklendi.');
    }

    public function importErpItemLocations(Request $request){
        Validator::validate($request->all(), [
            'importFile' => [ 'required','mimes:xlsx,xls']
        ]);
        Excel::import(new ErpItemLocationImport, $request->importFile);
        dd('Ürün Rafları Eklendi.');
    }

    public function importErpFinishedProducts(Request $request){
        ErpFinishedProduct::truncate();
        Validator::validate($request->all(), [
            'importFile' => [ 'required','mimes:xlsx,xls']
        ]);
        Excel::import(new ErpFinishedProducts, $request->importFile);
        dd('Bitmiş Ürünler Eklendi.');
    }

    public function index(){
        return view('dev-blade');
    }

    public function sqlUpdate(){
        $allitems = [6753,6728,6729,6730,6731,6734,6732,6733,6735,6736,6737,6738,6739,6740,6741,6742,6743,6744,6745,6746,6747,6748,7316,6749,6750,6751,6752,7313,7312,7317,6754,6755,7305,7306,7307,7308,7309,7310,7297,7298,7299,7300,7302,6764,7289,6765,7290,6766,7291,7293,6767,6768,7294,6769,7292,7131,7132,7133,7134,7135,7136,7117,7118,7119,7120,7121,7122,7110,7111,7112,7113,7114,7115,7103,7104,7105,7106,7107,6756,6757,6758,6759,6760,6761,6762,6763,6770,6771,6772,6773,6774,6775,6776,6777,6778,6779,6780,6781,6782,6783,6784,6785,6786,6787,6788,6789,6790,6791,6792,6793,6794,6795,6796,6797,6798,6799,6800,6801,6802,6803,6804,6805,6806,6807,6808,6809,6810,6811,6812,6813,6814,6815,6816,6817,6818,6819,6820,6821,6822,6823,6824,6825,6826,6827,6828,6829,6830,6831,6832,6833,6834,6835,6836,6837,6838,6839,6840,6841,6842,6843,6844,6845,6846,6847,6848,6849,6850,6851,6852,6853,6854,6855,6856,6857,6858,6859,6860,6861,6862,6863,6864,6865,6866,6867,6868,6869,6870,6871,6872,6873,6874,6875,6876,6877,6878,6879,6880,6881,6882,6883,6884,6885,6886,6887,6888,6889,6890,6891,6892,6893,6894,6895,6896,6897,6898,6899,6900,6901,6902,6903,6904,6905,6906,6907,6908,6909,6910,6911,6912,6913,6914,6915,6916,6917,6918,6919,6920,6921,6922,6923,6924,6925,6926,6927,6928,6929,6930,6931,6932,6933,6934,6935,6936,6937,6938,6939,6940,6941,6942,6943,6944,6945,6946,6947,6948,6949,6950,6951,6952,6953,6954,6955,6956,6957,6958,6959,6960,6961,6962,6963,6964,6965,6966,6967,6968,6969,6970,6971,6972,6973,6974,6975,6976,6977,6978,6979,6980,6981,6982,6983,6984,6985,6986,6987,6988,6989,6990,6991,6992,6993,6994,6995,6996,6997,6998,6999,7000,7001,7002,7003,7004,7005,7006,7007,7008,7009,7010,7011,7012,7013,7014,7015,7016,7017,7018,7019,7020,7021,7022,7023,7024,7025,7026,7027,7028,7029,7030,7031,7032,7033,7034,7035,7036,7037,7038,7039,7040,7041,7042,7043,7044,7045,7046,7047,7048,7049,7050,7051,7052,7053,7054,7055,7056,7057,7058,7059,7060,7061,7062,7063,7064,7065,7066,7067,7068,7069,7070,7071,7072,7073,7074,7075,7076,7077,7078,7079,7080,7081,7082,7083,7084,7085,7086,7087,7088,7089,7090,7091,7092,7093,7094,7095,7096,7097,7098,7099,7100,7101,7102,7108,7109,7116,7123,7124,7125,7126,7127,7128,7129,7130,7137,7138,7139,7140,7141,7142,7143,7144,7145,7146,7147,7148,7149,7150,7151,7152,7153,7154,7155,7156,7157,7158,7159,7160,7161,7162,7163,7164,7165,7166,7167,7168,7169,7170,7171,7172,7173,7174,7175,7176,7177,7178,7179,7180,7181,7182,7183,7184,7185,7186,7187,7188,7189,7190,7191,7192,7193,7194,7195,7196,7197,7198,7199,7200,7201,7202,7203,7204,7205,7206,7207,7208,7209,7210,7211,7212,7213,7214,7215,7216,7217,7218,7219,7220,7221,7222,7223,7224,7225,7226,7227,7228,7229,7230,7231,7232,7233,7234,7235,7236,7237,7238,7239,7240,7241,7242,7243,7244,7245,7246,7247,7248,7249,7250,7251,7252,7253,7254,7255,7256,7257,7258,7259,7260,7261,7262,7263,7264,7265,7266,7267,7268,7269,7270,7271,7272,7273,7274,7275,7276,7277,7278,7279,7280,7281,7282,7283,7284,7285,7286,7287,7288,7295,7296,7303,7304,7311,7314,7315,7318,7319,7320,7321,7322,7323,7324,7325,7326,7327,7328,7329,7330,7331,7332,7333,7334,7335,7336,7337,7338,7339,7340,7341,7342,7343,7344,7345,7346,7347,7348,7349,7350,7351,7352,7353,7354,7355,7356,7357,7358,7359,7360,7361,7362,7363,7364,7365,7366,7367,7368,7369,7370,7371,7372,7373,7374,7375,7376,7377,7378,7379,7380,7381,7382,7383,7384,7385,7386,7387,7388,7389,7390,7391,7392,7393,7394,7395,7396,7397,7398,7399,7400,7401,7402,
        ];
        $recipie1 = ["1440"=>1,"1442"=>1,"11388"=>2,"1359"=>1,"1414"=>1,"6626"=>1];
        $recipie2 = ["131"=>1,"11389"=>2,"1441"=>1,"1344"=>1,"1414"=>1,"6626"=>1];
        foreach ($allitems as $key => $value) {
            $new = new ErpProductionRecipe;
            $new->item_id = $value;
            $new->recipe_name = "6-4f - Serhat Acar";
            $new->recipe_creator_id = 28;
            // $new->recipe_id = null;
            // $new->amount = null;
            // $new->waste = null;
            $new->save();
            foreach ($recipie1 as $key => $value) {
                $new2 = new ErpProductionRecipe;
                $new2->item_id = $key;
                // $new2->recipe_name = null;
                $new2->recipe_creator_id = 28;
                $new2->recipe_id = $new->id;
                $new2->amount = $value;
                $new2->waste = 0;
                $new2->save();
            }
        }
        foreach ($allitems as $key => $value) {
            $new = new ErpProductionRecipe;
            $new->item_id = $value;
            $new->recipe_name = "5-3f - Serhat Acar";
            $new->recipe_creator_id = 28;
            // $new->recipe_id = null;
            // $new->amount = null;
            // $new->waste = null;
            $new->save();
            foreach ($recipie2 as $key => $value) {
                $new3 = new ErpProductionRecipe;
                $new3->item_id = $key;
                // $new3->recipe_name = null;
                $new3->recipe_creator_id = 28;
                $new3->recipe_id = $new->id;
                $new3->amount = $value;
                $new3->waste = 0;
                $new3->save();
            }
        }
    dd(
        'Query Injected'
    );

    }

} 