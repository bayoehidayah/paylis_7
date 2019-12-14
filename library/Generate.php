<?php
    //Disini bayu membuat default panjang generate nya sebanyak 12 karakter.
    //Jika ingin mengubahnya jangan diparamter fungsinya, tetapi :
    //number(56) <--- contoh dengan 56 karakter
    class Generate{

        public function number($panjang = 12){
            $allowed_char = "123456789";
            $random_char = "";

            for($i = 0; $i < $panjang; $i++){
                //Memilih acak
                $char = rand(0, strlen($allowed_char) - 1);
                
                //Menambah Karakter
                $random_char .= $allowed_char{$char};
            }

            return $random_char;
        }

        public function charater($panjang = 12){
            $allowed_char = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
            $random_char = "";

            for($i = 0; $i < $panjang; $i++){
                //Memilih acak
                $char = rand(0, strlen($allowed_char) - 1);
                
                //Menambah Karakter
                $random_char .= $allowed_char{$char};
            }

            return $random_char;
        }

        public function num_char($panjang = 12){
            $allowed_char = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz123456789";
            $random_char = "";

            for($i = 0; $i < $panjang; $i++){
                //Memilih acak
                $char = rand(0, strlen($allowed_char) - 1);
                
                //Menambah Karakter
                $random_char .= $allowed_char{$char};
            }

            return $random_char;
        }
    }
?>