<?php

namespace App\Http\Controllers;

use App\Server;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    private $password;

    function __construct() {
        $this->password = env('application_password',null);
    }

    private function cidrToRange($cidr) {
        $range = array();
        $cidr = explode('/', $cidr);
        $range[0] = long2ip((ip2long($cidr[0])) & ((-1 << (32 - (int)$cidr[1]))));
        $range[1] = long2ip((ip2long($cidr[0])) + pow(2, (32 - (int)$cidr[1])) - 1);
        return $range;
    }

    public function check($password)
    {
        $results = [];
        if($password == "tests"){
            $range = $this->cidrToRange("52.209.158.0/24");
            $start = explode('.',$range[0]);
            $end = explode('.',$range[1]);
            var_dump($start,$end);
            for($i = $start[2]; $i<=$end[2]; $i++) {
                for($j = $start[3]; $j<=$end[3]; $j++) {
                    echo 'result at '.$start[0].'.'.$start[1].'.'.$i.'.'.$j."\n";
                    $result = shell_exec('curl -s --connect-timeout 0,5 --max-time 0,8 -I '.$start[0].'.'.$start[1].'.'.$i.'.'.$j);
                    echo $result;
                    if($result != null) {
                        $searches = [
                            "php" => "X-Powered-By: PHP/",
                            "nginx" => "nginx/",
                            "apache" => "Apache/"
                        ];
                        $r = [];
                        $server = new Server();
                        $server->ip = $start[0].'.'.$start[1].'.'.$i.'.'.$j;
                        $applications = [];
                        $server->has_vulnerabilities = false;
                        foreach($searches as $k => $search) {
                            $r[$k] = strpos($result,$search);
                            if($r[$k] !== false) {
                                //pointer:
                                $pointer = $r[$k] + strlen($search);
                                $first_occurrence = strpos($result,'.',$pointer);
                                $ver_1 = substr($result, $pointer, $first_occurrence - $pointer);
                                $pointer = $first_occurrence+1;
                                $first_occurrence = strpos($result,'.',$pointer);
                                $ver_2 = substr($result, $pointer, $first_occurrence - $pointer);
                                $pointer = $first_occurrence+1;
                                $first_occurrence = strpos($result,"\r",$pointer);
                                $ver_3 = substr($result, $pointer, $first_occurrence - $pointer);
                                array_push($applications, "{
                                    'name':'$k',
                                    'version':'$ver_1.$ver_2.$ver_3',
                                    'vulnerabilities':''                                    
                                }");
                                $server->headers = $result;
                                $server->severity = 0;
                            }
                        }

                        if(count($applications)) {
                            $server->applications = json_encode($applications);
                            $server->save();
                        }
                        array_push($results, $result);
                    }
                }
            }
        }

        return $results;
    }

    public function index()
    {
        return Server::paginate(20);
    }
}
