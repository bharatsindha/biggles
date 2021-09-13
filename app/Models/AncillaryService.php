<?php

namespace App\Models;

use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class AncillaryService extends Model
{
    use SoftDeletes;

    protected $fillable = ['company_id',
        'type',
        'price',
        'premium',
        'basis',
        'add_ons',
        'valued_inventory',
        'boxes',
        'large_boxes',
        'small_boxes',
        'paper',
        'tape',
        'pickup_toggle',
        'pickup_depot',
        'delivery_toggle',
        'delivery_depot',
        'car_rego',
        'car_make',
        'car_model',
        'car_type',
        'cleaning_options',
        'carpet_area',
        'curtains',
        'blinds',
        'about',
        'questions',
        'created_by'];

    /**
     * @return BelongsTo
     */
    public function createdBy()
    {
        return $this->belongsTo('App\User', 'created_by')->withDefault();
    }

    /**
     * @return BelongsToMany
     */
    public function moves()
    {
        return $this->belongsToMany(Move::class, 'move_ancillary_rel', 'ancillary_id', 'move_id');
    }

    /**
     * Get All Ancillary Service Lists
     *
     * @param $request
     * @return JsonResponse
     * @throws Exception
     */
    public static function getAncillaryService($request)
    {
        $model = self::query();
        $user  = Auth::user();

        $cols = [
            "ancillary_services.*",
            'users.name as created_by',
            'conf1.option_value as type',
            'conf2.option_value as premium',
            'conf3.option_value as basis'
        ];
        $model->select($cols);

        # If request from particular job
        if (isset($request->moveId) && !empty($request->moveId)) {
            $model = $model->Join(
                'move_ancillary_rel', 'move_ancillary_rel.ancillary_id', '=', 'ancillary_services.id'
            );
            $model->where('move_ancillary_rel.move_id', $request->moveId);
        }

        $user = Auth::user();
        /*if ($user && $user->access_level == 1) {
            $model = $model->where('ancillary_services.company_id', $user->company_id);
        }*/

        $model = $model->leftJoin(
            'users', 'users.id', '=', 'ancillary_services.created_by'
        );
        $model = $model->leftJoin(
            'configurations as conf1', 'conf1.id', '=', 'ancillary_services.type'
        );
        $model = $model->leftJoin(
            'configurations as conf2', 'conf2.id', '=', 'ancillary_services.premium'
        );
        $model = $model->leftJoin(
            'configurations as conf3', 'conf3.id', '=', 'ancillary_services.basis'
        );

        return DataTables::eloquent($model)
            ->addColumn('action', function ($ancillary) use ($user) {
                $action = '<div class="d-flex align-items-center col-actions">';
                $action .= View('layouts.actions.view')->with('model', $ancillary)->with('route',
                    'ancillaryservice.show');
                $action .= View('layouts.actions.edit')->with('model', $ancillary)->with('route',
                    'ancillaryservice.edit');
                if ($user->access_level == 0) {
                    $action .= View('layouts.actions.delete')->with('model', $ancillary)->with('route',
                        'ancillaryservice.destroy');
                }
                return $action .= '</div>';
            })
            ->addColumn('created_at', function ($q) {
                return date("d F, Y g:i A", strtotime($q->created_at));
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Return all the options details of dropdown from configuration and using it in the form.
     *
     * @return mixed
     */
    public static function getOptionsFromConfiguration()
    {
        try {
            $data['companyOptions']          = Company::getCompanyOptions();
            $data['ancillaryType']           = Configuration::getOptionsByType('ancillary_type');
            $data['ancillaryPremium']        = Configuration::getOptionsByType('ancillary_premium');
            $data['ancillaryBasis']          = Configuration::getOptionsByType('ancillary_basis');
            $data['ancillaryPickupToggle']   = Configuration::getOptionsByType('ancillary_pickup_toggle');
            $data['ancillaryPickupDepot']    = Depot::getDepotOptions();
            $data['ancillaryDeliveryToggle'] = Configuration::getOptionsByType('ancillary_delivery_toggle');
            $data['ancillaryDeliveryDepot']  = $data['ancillaryPickupDepot'];
            $data['ancillaryCarType']        = Configuration::getOptionsByType('ancillary_car_type');

        } catch (Exception $e) {

            return null;
        }

        return $data;
    }

    /**
     * Select the options values from the configuration by option id to show in the ancillary view section.
     *
     * @param $ancillaryService
     * @return mixed
     */
    public static function setOptionsValueFromConfiguration($ancillaryService)
    {

        try {
            $ancillaryService->created_by      = User::getUserNameById($ancillaryService->created_by);
            $ancillaryService->type            = Configuration::getOptionValueById($ancillaryService->type);
            $ancillaryService->premium         = Configuration::getOptionValueById($ancillaryService->premium);
            $ancillaryService->basis           = Configuration::getOptionValueById($ancillaryService->basis);
            $ancillaryService->pickup_toggle   = Configuration::getOptionValueById($ancillaryService->pickup_toggle);
            $ancillaryService->pickup_depot    = Depot::getDepotOptionValueById($ancillaryService->pickup_depot);
            $ancillaryService->delivery_toggle = Configuration::getOptionValueById($ancillaryService->delivery_toggle);
            $ancillaryService->delivery_depot  = Depot::getDepotOptionValueById($ancillaryService->delivery_depot);
            $ancillaryService->car_type        = Configuration::getOptionValueById($ancillaryService->car_type);

        } catch (Exception $e) {

            return null;
        }

        return $ancillaryService;
    }

    /**
     * Return all the ancillaries for company
     *
     * @return Builder[]|Collection
     */
    public static function getAncillaries()
    {
        $ancObj = self::query();
        $cols   = [
            "ancillary_services.*",
            'conf1.option_value as type_val'
        ];
        $ancObj->select($cols);
        $ancObj->leftJoin(
            'configurations as conf1', 'conf1.id', '=', 'ancillary_services.type'
        );

        $user = Auth::user();
        if ($user && $user->access_level == 1) {
            $ancObj->where('company_id', $user->company_id);
        }

        return $ancObj->get();
    }

    /**
     * Return all the ancillaries for company
     *
     * @param $request
     * @return Builder[]|Collection
     */
    public static function getAncillaryServices($request)
    {
        $ancillaryObj = self::query();
        $cols         = [
            "ancillary_services.id",
            "ancillary_services.price",
            "ancillary_services.about",
            "ancillary_services.questions",
            'conf1.option_value as ancillary_type'
        ];
        $ancillaryObj->select($cols);
        $ancillaryObj->leftJoin(
            'configurations as conf1', 'conf1.id', '=', 'ancillary_services.type'
        );

        /*if (isset($request->companyId) && !is_null($request->companyId)){
            $ancillaryObj->where('ancillary_services.company_id', $request->companyId);
        }*/
        if (isset($request->ancillaryId) && !is_null($request->ancillaryId)){
            $ancillaryObj->whereNotIn('ancillary_services.id', $request->ancillaryId);
        }

        $ancillaryResult = $ancillaryObj->get();
        foreach ($ancillaryResult as $key => $value) {
            $ancillaryResult[$key]->questions = json_decode($value->questions);
        }

        return $ancillaryResult;
    }

    /**
     * Return the booked ancillary services
     *
     * @param $moveId
     * @return array
     */
    public static function getAncillaryServicePrice($moveId){
        $move = Move::find($moveId);
        $priceBreakdown = [];
        $totalPrice = 0;
        foreach ($move->ancillaryServices as $key => $ancillary){
            $priceBreakdown[$key]['type'] = Configuration::getOptionValueById($ancillary->type);
            $priceBreakdown[$key]['price'] = $ancillary->price;
            $totalPrice = $totalPrice+$ancillary->price;
        }
        $priceBreakdown['totalPrice'] = $totalPrice;

        return $priceBreakdown;
    }

    /**
     * Update the ancillaries of moves
     *
     * @param $request
     * @param $move
     * @return bool
     */
    public static function updateMoveAncillary($request, $move)
    {
        $syncData = [];

        foreach ($request->ancillaries as $key => $ancillary) {
            $syncData[$ancillary['ancillary_id']] = ['answers' => json_encode($ancillary['answers'])];
        }
        $move->ancillaryServices()->sync($syncData);

        return true;
    }
}
