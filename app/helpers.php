<?php
    if (! function_exists('calculate_date_diff')) {
        function calculate_date_diff($start, $finish)
        {
            $start = Carbon\Carbon::parse($start);
            $finish = Carbon\Carbon::parse($finish);
            $count_months = 0;

            if($start->format('Ym') == $finish->format('Ym')){
                $count_months = 0;
                // if($finish->format('d') > 30){
                //     $finish = Carbon\Carbon::parse($finish->addDays(-1)->format('Y-m-d'));
                // }
                $count_days = $start->diffInDays($finish) +1;
                if($finish->format('m') == 2 && ($count_days == 28 || $count_days == 29)){
                    $count_days = 30;
                }
                // $count_days = $count_days > 30 ? 30 : $count_days;
            }else{
                $count_months = 0;
                // if($start->format('d') > 30){
                //     $start = Carbon\Carbon::parse($start->addDays()->format('Y-m-d'));
                // }
                $start_day = $start->format('d');
                $count_days = 30 - $start_day +1;
                $start = Carbon\Carbon::parse($start->addMonth()->format('Y-m').'-01');
                while ($start <= $finish) {
                    $count_months++;
                    $start->addMonth();
                }
                $count_months--;

                // Calcula la cantidad de días del ultimo mes
                $count_days_last_month = $start->subMonth()->diffInDays($finish) +1;
                // Si es mayor o igual a 30 se toma como un mes completo
                // if($count_days_last_month >= 30 || ($finish->format('m') == 2 && $count_days_last_month == 28)){
                //     $count_days_last_month = 0;
                //     $count_months++;
                // }
                $count_days += $count_days_last_month;
            }

            // if($count_days >= 30){
            //     $count_months++;
            //     $count_days -= 30;
            // }

            return json_decode(json_encode(['months' => $count_months, 'days' => $count_days]));
        }
    }