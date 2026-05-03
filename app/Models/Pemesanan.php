<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Pemesanan extends Model {
    use HasFactory;
    protected $fillable = ['kode_pemesanan','user_id','jadwal_id','tanggal_pesan','jam_penjemputan','titik_penjemputan','titik_tujuan','jumlah_penumpang','jumlah_bagasi','biaya_bagasi_tambahan','total_harga','status','catatan','barcode'];
    protected function casts(): array { return ['tanggal_pesan'=>'datetime','total_harga'=>'decimal:2','biaya_bagasi_tambahan'=>'decimal:2']; }
    public function user() { return $this->belongsTo(User::class); }
    public function jadwal() { return $this->belongsTo(Jadwal::class); }
    public function kursis() { return $this->hasMany(PemesananKursi::class); }
    public function getStatusLabelAttribute(): string { return match($this->status){'akan_datang'=>'Akan Datang','selesai'=>'Selesai','dibatalkan'=>'Dibatalkan',default=>ucfirst($this->status)}; }
    public function getStatusBadgeAttribute(): string { return match($this->status){'akan_datang'=>'badge-akan-datang','selesai'=>'badge-selesai','dibatalkan'=>'badge-dibatalkan',default=>'badge-secondary'}; }
    public function getJamPenjemputanFormatAttribute(): string { return $this->jam_penjemputan ? substr($this->jam_penjemputan,0,5) : '-'; }
    public static function generateKode(): string { $prefix='GMA'; $date=now()->format('ymd'); $last=static::whereDate('created_at',today())->count()+1; return sprintf('%s-%s-%03d',$prefix,$date,$last); }
}
