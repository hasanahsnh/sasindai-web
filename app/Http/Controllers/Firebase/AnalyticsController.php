<?php

namespace App\Http\Controllers\Firebase;

use App\Http\Controllers\Controller;
use Google\Cloud\BigQuery\BigQueryClient;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    //
    function index() {
        $projectId = env('GOOGLE_BIGQUERY_PROJECT');
        $bigQuery = new BigQueryClient([
            'projectId' => $projectId,
            'keyFilePath' => base_path(env('GOOGLE_APPLICATION_CREDENTIALS'))
        ]);
    }
}
