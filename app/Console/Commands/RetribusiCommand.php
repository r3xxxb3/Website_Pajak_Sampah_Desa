<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Properti;
use App\Pelanggan;
use App\JenisJasa;
use App\Retribusi;
use App\StandarRetribusi;



class RetribusiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'retribusi:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $properti = Properti::where('status', 'terverifikasi')->get();
        // $this->info("test");
        foreach($properti as $prop){
            $retribusi = Retribusi::where('id_properti', $prop->id)->orderBy('created_at', 'DESC')->first();
            $standar = StandarRetribusi::where('id_jenis_jasa', $prop->id_jenis)->where('id_desa_adat', $prop->id_desa_adat)->where(function ($query){
                $query->where('tanggal_berlaku', '<=', now())->orWhere('tanggal_selesai', '>=', now())->orWhere('active', 1);
            })->first();
            // $stanres = $standar->where('tanggal_berlaku', '<=', now())->where('tanggal_selesai', '>=', now())->Where('active', 1)->first();
            // dd($properti);
            if(!isset($retribusi)){
                if(isset($standar)){
                    $retribusi = new Retribusi;
                    $retribusi->id_pelanggan = $prop->id_pelanggan;
                    $retribusi->id_properti = $prop->id;
                    $retribusi->status = "pending";
                    $retribusi->nominal = $standar->nominal_retribusi;
                    $retribusi->save();
                }else{
                    continue;
                }
            }elseif($retribusi->created_at->format('m') != now()->format('m') || $retribusi->created_at->format('y') < now()->format('y') ){
                if(isset($standar)){
                    $retribusi = new Retribusi;
                    $retribusi->id_pelanggan = $prop->id_pelanggan;
                    $retribusi->id_properti = $prop->id;
                    $retribusi->status = "pending";
                    $retribusi->nominal = $standar->nominal_retribusi;
                    $retribusi->save();
                }else{
                    continue;
                }
            }else{
                continue;
            }
        }
        Log::info("Testing Scheduler");
        // return 0;

    }
}
