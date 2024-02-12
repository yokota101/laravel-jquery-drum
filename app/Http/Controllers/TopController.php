<?php

namespace App\Http\Controllers;

use App\Consts\ProjectConst;
use App\Models\Category;
use App\Repositories\TopRepository;
use Illuminate\Http\Request;

/**
 * トップページに関するクラス
 */
class TopController extends Controller
{
    protected $topRepository;
    protected $catModel;

    public function __construct()
    {
        $this->topRepository = new TopRepository;
        $this->catModel = new Category;
    }
    /**
     * トップページの初期データ取得
     */
    public function index()
    {
        // カテゴリ取得
        $categories = $this->catModel->getCategoryList()->get();

        // 総合ランキング取得
        $totalRanks = $this->topRepository->makeTotalRankQuery()
            ->orderBy('tb2.total', 'desc')
            ->orderBy('t1.created_at', 'desc')
            ->limit(5)->get();

        $recentPosts = $this->topRepository->makeRecentPostQuery()
            ->orderBy('posts.created_at', 'desc')
            ->limit(5)->get();

        $modifiedCategories = $categories->pluck('category_name', 'value')->toArray();

        return view('top', ['totalranks' => $totalRanks, 'categories' => $categories, 'recentposts' => $recentPosts, 'modifiedCategories' => $modifiedCategories]);
    }

    /**
     * カテゴリを選んだ時に該当カテゴリの動画を取得する
     */
    public function get(Request $request)
    {
        $category_id = $request->category_id;

        if ($request->sub_cat_flg === 'true') {
            $subCatFlg = true;
        } else {
            $subCatFlg = false;
        }

        // 総合ランキング取得
        /* note 共通処理はRepositoryクラスで共通化している */
        $totalRanks = $this->topRepository->makeTotalRankQuery();

        if ($category_id != "0") {
            if ($subCatFlg) {
                // サブカテゴリを含む
                $totalRanks->where(function ($q) use ($category_id) {
                    $q->Where('t1.main_category_id', $category_id)
                        ->orWhere('t1.sub_category_id_first', $category_id)
                        ->orWhere('t1.sub_category_id_second', $category_id)
                        ->orWhere('t1.sub_category_id_third', $category_id);
                });
            } else {
                // サブカテゴリを含まない
                $totalRanks->where(function ($q) use ($category_id) {
                    $q->where('t1.main_category_id', $category_id);
                });
            }
        }

        $totalRanks->orderBy('tb2.total', 'desc')
            ->orderBy('t1.created_at', 'desc')
            ->limit(5);

        return  $totalRanks->get();
    }

    /**
     * カテゴリを選んだ時に該当カテゴリの動画を取得する(ランキングページ)
     */
    public function getRankList(Request $request)
    {
        $category_id = $request->category_id;
        $start = $request->start;

        if ($request->sub_cat_flg === 'true') {
            $subCatFlg = true;
        } else {
            $subCatFlg = false;
        }

        // 総合ランキング取得
        $totalRanks = $this->topRepository->makeTotalRankQuery();

        if ($category_id != "0") {
            if ($subCatFlg) {
                // サブカテゴリを含む
                $totalRanks->where(function ($q) use ($category_id) {
                    $q->Where('t1.main_category_id', $category_id)
                        ->orWhere('t1.sub_category_id_first', $category_id)
                        ->orWhere('t1.sub_category_id_second', $category_id)
                        ->orWhere('t1.sub_category_id_third', $category_id);
                });
            } else {
                // サブカテゴリを含まない
                $totalRanks->where(function ($q) use ($category_id) {
                    $q->where('t1.main_category_id', $category_id);
                });
            }
        }

        $totalRanks->orderBy('tb2.total', 'desc')
            ->orderBy('t1.created_at', 'desc');

        if (isset($start)) {
            $totalRanks = $totalRanks->offset($start)->limit(ProjectConst::DISP_PER_PAGE)->get();
        } else {
            $totalRanks = $totalRanks->get();
        }
        return ['totalranks' => $totalRanks, 'cnt' => $totalRanks->count()];
    }

    /**
     * カテゴリを選んだ時に該当カテゴリの動画を取得する(アーカイブページ)
     */
    public function getArchiveList(Request $request)
    {
        $category_id = $request->category_id;
        $start = $request->start;

        if ($request->sub_cat_flg === 'true') {
            $subCatFlg = true;
        } else {
            $subCatFlg = false;
        }

        // 総合ランキング取得
        $totalRanks = $this->topRepository->makeTotalRankQuery();

        if ($category_id != "0") {
            if ($subCatFlg) {
                // サブカテゴリを含む
                $totalRanks->where(function ($q) use ($category_id) {
                    $q->Where('t1.main_category_id', $category_id)
                        ->orWhere('t1.sub_category_id_first', $category_id)
                        ->orWhere('t1.sub_category_id_second', $category_id)
                        ->orWhere('t1.sub_category_id_third', $category_id);
                });
            } else {
                // サブカテゴリを含まない
                $totalRanks->where(function ($q) use ($category_id) {
                    $q->where('t1.main_category_id', $category_id);
                });
            }
        }

        $totalRanks->orderBy('t1.created_at', 'desc')
            ->orderBy('tb2.total', 'desc');
        if (isset($start)) {
            $totalRanks = $totalRanks->offset($start)->limit(ProjectConst::DISP_PER_PAGE)->get();
        } else {
            $totalRanks = $totalRanks->get();
        }
        return ['totalranks' => $totalRanks, 'cnt' => $totalRanks->count()];
    }

    /**
     * ランキング一覧を取得(初回)
     */
    public function ranking(Request $request)
    {

        // 総合ランキング取得
        $totalRanks = $this->topRepository->makeTotalRankQuery()
            ->orderBy('tb2.total', 'desc')
            ->orderBy('t1.created_at', 'desc');

        $cnt = $totalRanks->get()->count();
        $totalRanks = $totalRanks->limit(ProjectConst::DISP_PER_PAGE)->get();

        // カテゴリ取得
        $cat = new Category;
        $categories = $cat->getCategoryList()->get();

        $modifiedCategories = $categories->pluck('category_name', 'value')->toArray();

        return view('ranking', ['totalranks' => $totalRanks, 'categories' => $categories, 'modifiedCategories' => $modifiedCategories, 'rankCount' => $cnt]);
    }

    /**
     * 過去の記事一覧を取得(初回)
     */
    public function archive(Request $request)
    {

        // 総合ランキング取得
        $totalRanks = $this->topRepository->makeTotalRankQuery();

        $cnt = $totalRanks->get()->count();
        $totalRanks = $totalRanks->limit(ProjectConst::DISP_PER_PAGE)->get();

        // カテゴリ取得
        $categories = $this->catModel->getCategoryList()->get();

        $modifiedCategories = $categories->pluck('category_name', 'value')->toArray();

        return view('archive', ['totalranks' => $totalRanks, 'categories' => $categories, 'modifiedCategories' => $modifiedCategories, 'archiveCount' => $cnt]);
    }
}
