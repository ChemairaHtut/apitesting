<?php
    namespace App\Http\Traits;

    use Illuminate\Support\Str;
    use Illuminate\Http\UploadedFile;
    use Illuminate\Support\Facades\Storage;
    trait FileUploadTrait
    {
        
        public function uploadFile(UploadedFile $file, string $path): string
        {
            $fileName = Str::random(32) . '.' . $file->getClientOriginalExtension();
            $file->storeAs($path, $fileName, 'public');
            return $fileName;
        }
        public function UpdateImage($file,string $path,$oldImage){
            if($oldImage){
                Storage::disk('public')->delete("{$path}/".$oldImage);
            }
            $fileName = Str::random(32).".".$file->getClientOriginalExtension();
            $file->storeAs($path,$fileName,'public');
            return $fileName;
        }
    }
    
?>