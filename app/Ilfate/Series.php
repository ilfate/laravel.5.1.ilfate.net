<?php

namespace Ilfate;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class Series extends Model
{
    const PATH_TO_IMAGES = '/images/game/guess/';

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'series';

    /**
     * @param bool|false $difficulty
     * @param array      $exclude
     *
     * @return mixed
     */
    public static function getRandomSeries($difficulty = false, $exclude = array())
    {
        $query = self::where('active', '=', 1);
        if ($difficulty) {
        	$query = $query->where('difficulty', '=', $difficulty);
        }
        if ($exclude) {
            $query = $query->whereNotIn('id', $exclude);
        }
        $series = $query->orderByRaw("RAND()")->first();
        return $series;
    }

    /**
     * @param $seriesId
     *
     * @return mixed
     */
    public function getImagesBySeriesId($seriesId)
    {
        $images = SeriesImage::where('series_id', '=', (int) $seriesId)->get();
        return $images;
    }

    /**
     * @param array $images
     *
     * @return array
     */
    public function sortImagesByDifficulty(Collection $images)
    {
        $sortedImages = [1 => [], 2 => [], 3 => []];
        foreach ($images as $value) {
            $sortedImages[$value['difficulty']][] = $value->toArray();
        }
        return $sortedImages;
    }

    /**
     * @param $imageId
     *
     * @return mixed
     */
    public function deleteImageById($imageId)
    {
        $image = SeriesImage::select('id', 'url', 'series_id')->where('id', $imageId)->first();
        $filename = public_path() . self::PATH_TO_IMAGES . $image->url;
        $seriesId = $image->series_id;
        if (file_exists($filename)) {
            unlink($filename);
            SeriesImage::where('id', '=', $imageId)->delete();
            return $seriesId;
        }
        return false;
    }

    /**
     * @param $seriesId
     */
    public function toggleActive($seriesId)
    {
        $series = Series::where('id', $seriesId)->first();
        if ($series->active) {
            $series->active = 0;
        } else {
            $series->active = 1;
        }
        $series->save();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return bool
     */
    public function addImage(\Symfony\Component\HttpFoundation\File\UploadedFile $file, $seriesId, $difficulty)
    {
        $destinationPath = public_path() . self::PATH_TO_IMAGES;

        $extension = $file->getClientOriginalExtension();
        $filename = str_random(16) . '.' . $extension;
        $fileInPath = public_path() . self::PATH_TO_IMAGES . $filename;
        while (file_exists($fileInPath)) {
            $filename = str_random(16) . '.' . $extension;
            $fileInPath = public_path() . self::PATH_TO_IMAGES . $filename;
        }
        $upload_success = $file->move($destinationPath, $filename);

        if ($upload_success) {
            $fileRaw = file_get_contents($destinationPath . $filename);
            $image = new SeriesImage();
            $image->image = $fileRaw;
            $image->url = $filename;
            $image->series_id = $seriesId;
            $image->difficulty = $difficulty;

            return $image->save();
        } else {
            return false;
        }
    }

    /**
     * @param null $seriesId
     */
    public function generateImagesFromDatabase($seriesId = null)
    {
        if ($seriesId) {
            $images = SeriesImage::where('series_id', '=', $seriesId)->get();
        } else {
            $images = SeriesImage::get();
        }
        foreach ($images as $image) {
            file_put_contents(public_path() . Series::PATH_TO_IMAGES . $image->url, $image->image);
        }
    }

}
