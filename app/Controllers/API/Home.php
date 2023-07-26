<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;

class Home extends BaseController
{
    public function getTransaction() {
        // Your raw query
        $query = "
        SELECT 
            DAYNAME(waktu) AS day_of_week,
            SUM(total_bayar) AS total_bayar_sum
        FROM 
            pesanan
        WHERE 
            WEEKDAY(waktu) BETWEEN 0 AND 6   -- 0: Monday, 6: Sunday
        GROUP BY 
            day_of_week
        ORDER BY 
            MIN(waktu);
        ";

         $db = db_connect();
 
         $builder = $db->query($query);
 
        $results = $builder->getResult();
        return $this->response->setJSON($results);
    }
}
