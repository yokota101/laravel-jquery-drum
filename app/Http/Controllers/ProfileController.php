<?php

namespace App\Http\Controllers;

use App\Models\SocialAccount;
use App\Models\Post;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * プロフィール設定に関するクラス
 */
class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $id = Auth::id();

        // id取得できなければログインしていないためエラー
        if (empty($id)) return view('errors.404');

        return view('profile-edit', ['userinfo' => Auth::user()]);
    }

    /**
     * アカウントの削除
     * 
     */
    public function deleteAccount(Request $request)
    {
        $id = Auth::id();

        // id取得できなければログインしていないためエラー
        if (empty($id)) return view('errors.404');

        try {
            // アカウント削除
            User::where('id', $id)->update(['deleted_at' => now()]);

            // ソーシャルアカウント削除
            SocialAccount::where('user_id', $id)->update(['deleted_at' => now()]);

            // 記事削除
            Post::where('user_id', $id)->update(['deleted_at' => now()]);

            // vote削除
            Post::where('user_id', $id)->update(['deleted_at' => now()]);
        } catch (Exception $e) {
            Log::error('アカウント削除時にエラーが発生しました。',  $e->getMessage());
        }
        // ログアウトさせる
        Auth::logout();
        return;
    }
}
