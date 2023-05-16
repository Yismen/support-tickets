<?php

namespace Dainsys\Support\Services;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic;

class ImageCreatorService
{
    protected int $resize;
    protected int $quality;
    protected $encode;

    public function resize(int $resize): self
    {
        $this->resize = $resize;
        return $this;
    }

    public function quality(int $quality): self
    {
        $this->quality = $quality;
        return $this;
    }

    public function encode($encode): self
    {
        $this->encode = $encode;
        return $this;
    }

    /**
     * Create image and return url string
     *
     * @param  \Illuminate\Http\UploadedFile $image
     * @param  string                        $path
     * @param  string                        $name
     * @param  string                        $disk
     * @return string
     */
    public function make(UploadedFile $image, string $path, string $name, int $resize = 450, int $quality = 90, $encode = null): string
    {
        $this->resize = $resize;
        $this->quality = $quality;
        $this->encode = $encode;

        $name = str($name)->contains('.') ? $name : "{$name}.{$image->extension()}";
        $url = $path . '/' . $name;
        $disk = config('support.images_disk');

        $img = ImageManagerStatic::make($image)
            ->resize($this->resize, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->encode($this->encode, $this->quality);

        Storage::disk($disk)->put($url, $img);

        return $url ;
    }

    public function delete($files)
    {
        Storage::delete($files);
    }
}
