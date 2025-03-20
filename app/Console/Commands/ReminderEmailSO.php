<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

use App\Mail\DocumentRejectedMail;
use Illuminate\Support\Facades\Mail;

use App\Models\PenomoranKontrak;

class ReminderEmailSO extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remindso:schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for not registered so in H+7';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $query =
        "SELECT *
        FROM penomoran_kontrak
        WHERE (so_id = '' OR so_id = '-') AND supplier_id = '' AND DATEDIFF(CURDATE(), date_created) >= 7 ";

        $que = DB::connection('mysql')->select($query);

        $messageReminder = '';

        if($que){

            $total_data = count($que);

            $messageReminder .= 'Dear Tim Legal,<br><br>' .
                                            'There are '. $total_data .' SO numbers that has not been registered yet with the following details.' . '<br><br>' .
                                            '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse; width: 100%;">
                                            <thead>
                                                    <tr>
                                                        <th>SO Number</th>
                                                        <th>Contract Number</th>
                                                        <th>Date Created</th>
                                                        <th>Customer Name</th>
                                                        <th>PIC</th>
                                                    </tr>
                                                </thead>
                                                <tbody>';

            foreach($que as $record){

                $no_kontrak_compnet = $record->no_kontrak_compnet ?? '';
                $no_kontrak_customer = $record->no_kontrak_customer ?? '';

                if ($no_kontrak_customer && $no_kontrak_compnet) {
                    $no_kontrak = $no_kontrak_compnet . ' | ' . $no_kontrak_customer;
                } elseif ($no_kontrak_customer) {
                    $no_kontrak = $no_kontrak_customer;
                } elseif ($no_kontrak_compnet) {
                    $no_kontrak = $no_kontrak_compnet;
                }

                $messageReminder .= '<tr>
                                        <td style="border: 1px solid #000;">' . $record->so_id . '</td>'.
                                        '<td style="border: 1px solid #000;">' . $no_kontrak . '</td>'.
                                        '<td style="border: 1px solid #000;">' . $record->date_created . '</td>'.
                                        '<td style="border: 1px solid #000;">' . $record->customer_name . '</td>'.
                                        '<td style="border: 1px solid #000;">' . $record->nama_uploader . '</td>'.
                                    '</tr>';

                 // $messageReminder = 'Dear Bapak/Ibu ' .  $record->nama_uploader . ',<br><br>' .
                 //                     'Your SO number with the following details has not been registered yet.' . '<br><br>' .
                 //                     '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse; width: 100%;">
                 //                         <tr>
                 //                             <td style="border: 1px solid #000;">Contract Number</td>
                 //                             <td style="border: 1px solid #000;">' . $no_kontrak . '</td>
                 //                         </tr>
                 //                         <tr>
                 //                             <td style="border: 1px solid #000;">Project Number</td>
                 //                             <td style="border: 1px solid #000;">-</td>
                 //                         </tr>
                 //                         <tr>
                 //                             <td style="border: 1px solid #000;">Customer Name</td>
                 //                             <td style="border: 1px solid #000;">Not Registered Yet</td>
                 //                         </tr>
                 //                     </table>' . '<br><br>' .
                 //                     'Please fill the SO number through the following link:  <a href="' . url('kontrak/listPenomoranKontrak') . '">Link Penomoran Kontrak</a>.' . '<br><br><br>' .
                 //                     'Thank you.';

                // Mail::to($record->email_uploader)->send(new DocumentRejectedMail($messageReminder));

                // PenomoranKontrak::where('id', $record->id)->update(['reminder_sent' => 1]);
            }

            $messageReminder .= '</tbody></table><br><br>Please register the SO number through the following link: <a href="' . url('kontrak/listPenomoranKontrak') . '">Link Penomoran Kontrak</a><br><br>Thank you.';

            $queryEmail = "SELECT full_name, email, department_id, department, `active`
                           FROM employee
                           WHERE department_id = 10 AND `active` = '1' AND email IS NOT NULL";

            // $queryEmail = "SELECT full_name, email, department, title
            //                  FROM employee
            //                  WHERE department = 'IT Support' && title = 'Magang'";

            $queEmail = DB::connection('mysqlHRD')->select($queryEmail);

            // foreach($queEmail as $dataEmail){
            //     Mail::to($dataEmail->email)->send(new DocumentRejectedMail($messageReminder));
            // }

            // Mengambil kolom 'email' dari setiap hasil query untuk membuat array penerima
            $recipients = array_map(function($item) {
                return $item->email;
            }, $queEmail);

            // Mengirim email ke banyak penerima
            Mail::to($recipients)->send(new DocumentRejectedMail($messageReminder));

        }

        return 0;
    }
}
