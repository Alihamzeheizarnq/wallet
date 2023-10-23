<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

trait Locker
{

    /**
     * lock
     *
     * @param Model ...$models
     * @return array
     */
    public function lock(Model ...$models): array
    {
        $modelFiles = [];
        foreach ($models as $model) {
            $lockFile = $model->id . class_basename($model) . ".lock";
            $lockHandle = fopen($lockFile, "w");

            if ($lockHandle === false || !flock($lockHandle, LOCK_EX)) {
                throw new BadRequestException('the model is locked. please try later');
            }

            $modelFiles[] = $lockHandle;
        }

        return $modelFiles;
    }

    /**
     * unlock
     *
     * @param array $modelFiles
     * @return void
     */
    function unlock(array $modelFiles): void
    {
        foreach ($modelFiles as $modelFile) {
            if ($modelFile) {
                flock($modelFile, LOCK_UN);
                fclose($modelFile);
            }
        }
    }
}
