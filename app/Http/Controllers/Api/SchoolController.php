<?php

namespace App\Http\Controllers\Api;

use App\Models\School;

class SchoolController extends BaseController
{
    
    /**
     * @SWG\Get(
     *     path="/schools",
     *     summary="学校列表",
     *     tags={"schools"},
     *     description="学校列表",
     *     security={{"api_key": {"scope"}}},
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="结果集",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="code", type="integer", description="状态码"),
     *             @SWG\Property(property="message", type="string", description="状态信息"),
     *             @SWG\Property(property="data",type="array",
     *                  @SWG\Items(type="object",
     *                      @SWG\Property(property="initial",type="array",
     *                          @SWG\Items(type="object",
     *                              @SWG\Property(property="id", type="integer", description="学校id"),
     *                              @SWG\Property(property="name", type="string", description="学校名称"),
     *                          ),
     *                      ),
     *                  ),
     *              ),
     *         )
     *     ),
     * )
     */
    public function schools() {
        $schools = School::all();
        $schoolsKeyByInitial = [];
        foreach ($schools as $school) {
            $schoolsKeyByInitial[$school->initial][] = [
                'id' => $school->id,
                'name' => $school->name,
            ];
        }
        
        return $this->responseData($schoolsKeyByInitial);
    }
}
