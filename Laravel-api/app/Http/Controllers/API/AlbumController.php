<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Album;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class AlbumController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/getAlbumPet",
     *      operationId="getAlbumsList",
     *      tags={"Albums"},
     *      summary="Lấy danh sách pet của album",
     *      description="Trả về tất cả album",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          ),
     *      ),
     *      security={{"sanctum": {}}}
     * )
     */
    public function index()
    {
        $pets = Album::all();
        $pets->map(function ($user) {
            return [
                'user_id' => $user->id,
                'user_email' => $user->user->email,
                'username' => $user->user->name, // album->user()
            ];
        });
        return response()->json([
            'status' => 200,
            'pets' => $pets,
        ]);
    }
    /**
     * @OA\Post(
     * path="/api/store-albumPet",
     * summary="create album",
     * description="create album",
     * operationId="storeAlbum",
     * tags={"Albums"},
     * @OA\RequestBody(
     *    required=true,
     *    description="kiểm tra thông tin",
     *    @OA\MediaType(
     *        mediaType="multipart/form-data",
     *        @OA\Schema(
     *           @OA\Property(
     *           property="category_id",
     *           type="string",
     *           enum={"option1", "option2", "option3"},
     *           example="Chó"
     *           ),
     *            @OA\Property(property="emotion", type="string", example="Chó này xinh quá"),
     *            @OA\Property(
     *                property="image_pet",
     *                type="file",
     *                description="Hình ảnh",
     *                example="pet.png"
     *            ),
     *            required={"category_id","emotion", "image_pet"}
     *        )
     *    )
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Bạn phải điền các trường trên",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Bạn phải điền tất cả!")
     *        )
     *     )
     * )
     */
    public function store(Request $request)
    {
        if (auth('sanctum')->check()) {
            $validator = Validator::make(
                $request->all(),
                [
                    'category_id' => 'required|max:191',
                    'emotion' => 'required|max:191',
                    'image_pet' => 'required|image|mimes:jpeg,png,jpg|max:15360',
                ],
                [
                    'required' => 'Bạn phải điền :attribute'
                ]
            );
            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,
                    'errors' => $validator->messages(),
                ]);
            } else {
                $user_id = auth('sanctum')->user()->id;
                $pet = new Album;
                $pet->user_id = $user_id;
                $pet->category_id = $request->input('category_id');
                $pet->emotion = $request->input('emotion');
                if ($request->hasFile('image_pet')) {
                    $file = $request->file('image_pet');
                    $extension = $file->getClientOriginalExtension();
                    $filename = time() . '.' . $extension;
                    $file->move('uploads/album/', $filename);
                    $pet->image_pet = 'uploads/album/' . $filename;
                }
                $pet->save();
                return response()->json([
                    'status' => 200,
                    'message' => 'Thêm thú cưng thành công.',
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Bạn phải đăng nhập!',
            ], 401);
        }
    }
}
