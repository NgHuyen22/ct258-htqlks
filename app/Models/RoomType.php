<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RoomType extends Model
{
    protected $rt ="room_type"   ;
    public function getRoomType(){
        return $result = DB::table($this -> rt) 
                                ->where('status' , 1)
                                ->get();
    }
    
    public function getRoomTypeID($id_rt){
        return $result = DB::table($this -> rt) 
                                ->where('status' , 1)
                                ->where('id_lp' , $id_rt)
                                ->first();
    }

    public function getAllRoom($keywords = null){
         $result = DB::table($this -> rt) 
                                ->where('status' , 1);
                                // ->get();
                                // ->paginate(3);
                                if (!empty($keywords)) {
                                    $result->where(function ($query) use ($keywords) {
                                        // Kiểm tra nếu chuỗi có chứa số
                                        if (preg_match('/\d/', $keywords)) {
                                            // Loại bỏ các ký tự không phải số và dấu chấm
                                            $cleanKeywords = preg_replace('/[^0-9]/', '', $keywords);
                                            $query->orWhere('gia_lp', '=', $cleanKeywords)
                                                  ->orWhereRaw("DATE_FORMAT(created_at, '%Y-%m-%d') like ?", ['%' . $cleanKeywords . '%']) 
                                                  ->orWhereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') like ?", ['%' . $cleanKeywords . '%']);
                                        } else {
                                            // Nếu không có số thì tìm trong các trường văn bản
                                            $query->orWhere('ten_lp', 'like', '%' . $keywords . '%')
                                                  ->orWhere('tien_nghi', 'like', '%' . $keywords . '%');
                                        }
                                    });
                                }

                          return $result ->paginate(6);     
    }

    public function updateRoomType($id_rt ,$data){
        return $result = DB::table($this -> rt) 
                                ->where('status' , 1)
                                ->where('id_lp' , $id_rt)
                                ->update($data);
                                
    }
    public function countRoomType(){
        return $result = DB::table($this -> rt)
                            ->where('status' , 1)
                            ->count('id_lp');    
    }


    public function checkNameRT($ten_lp){
        return $result = DB::table($this->rt)
                            ->where('ten_lp', $ten_lp)
                            ->where('status', 1)
                            ->first();
    }

    public function insertRoomType($data){
       return  $result = DB::table($this->rt)
                                ->insert($data);
    }

    public function deleteRoomType($id_rt, $data){
        return $result = DB :: table($this -> rt)
                                ->where('id_lp', $id_rt)
                                ->where('status', 1)
                                ->update($data);
                                // ->delete();
    }

    public function giaLP($id_rt){
        return $result = DB :: table($this -> rt)
                            ->where('id_lp', $id_rt)
                            ->where('status', 1)
                            ->value('gia_lp');
                           
    }
}