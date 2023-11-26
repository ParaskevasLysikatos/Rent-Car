<?php

namespace App\Http\Controllers;

use App\Agent;
use App\Contact;
use Illuminate\Http\Request;
use Lang;

class CommonController extends Controller
{
    private static $Modalmappings = [
        Contact::class => 'contacts.form',
        Agent::class => 'agents.form',
    ];

    private static $mappings = [
        Contact::class => 'contact',
        Agent::class => 'agent',
    ];

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function editModal(Request $data) {
        $modal = $data['modal'];
        $key = $data['model']['key'];
        $class = $data['model']['class'];
        if (in_array($class, array_keys(static::$Modalmappings))) {
            $modal = static::$Modalmappings[$class];
        }
        if (in_array($class, array_keys(static::$mappings))) {
            $key = static::$mappings[$class];
        }
        $model = (new $class)::find($data['model']['value']);
        $lng = Lang::locale();

        // dd($model);
        return view($modal)->with([$key => $model, 'lng' => $lng]);
    }

    public function addModal(Request $data) {
        $depends = $data['depends'];
        $modal = $data['view'];
        $add_fields = $data['add_fields'];
        $lng = Lang::locale();
        $params = $depends;
        $params['lng'] = $lng;
        if ($add_fields) {
            foreach ($add_fields as $key => $value) {
                $params[$key] = $value;
            }
        }
        return view($modal)->with($params);
    }
}
