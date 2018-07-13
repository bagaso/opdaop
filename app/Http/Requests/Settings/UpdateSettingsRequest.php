<?php

namespace App\Http\Requests\Settings;

use App\Rules\Settings\CronCheckFormatRule;
use App\Rules\Settings\DataAllowanceFormatRule;
use App\Rules\Settings\DataAllowanceInputRule;
use App\Rules\Settings\TrialPeriodCheckFormatRule;
use App\Rules\Settings\TrialPeriodInputRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(auth()->user()->can('MANAGE_SITE_SETTINGS')) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'site_name' => 'bail|required',
            'site_url' => 'bail|required',
            'site_maintenance_mode' => 'bail|required|integer|in:1,0',
            'backup' => 'bail|required|integer|in:1,0',
            'backup_cron' => [
                'bail',
                'required',
                new CronCheckFormatRule,
            ],
            'trial_period' => [
                'bail',
                'required',
                new TrialPeriodCheckFormatRule,
                new TrialPeriodInputRule
            ],
            'data_reset' => 'bail|required|integer|in:1,0',
            'data_reset_cron' => [
                'bail',
                'required',
                new CronCheckFormatRule,
            ],
            'data_allowance' => [
                'bail',
                'required',
                new DataAllowanceFormatRule,
                new DataAllowanceInputRule,
            ],
            'enable_authorized_reseller' => 'bail|required|integer|in:1,0',
            'public_authorized_reseller' => 'bail|required|integer|in:1,0',
            'enable_server_status' => 'bail|required|integer|in:1,0',
            'public_server_status' => 'bail|required|integer|in:1,0',
            'enable_online_users' =>'bail|required|integer|in:1,0',
            'public_online_users' =>'bail|required|integer|in:1,0',
            'max_credit_transfer' => 'bail|required|integer',
            'renewal_qualified' => 'bail|required|integer',
        ];
    }
}
