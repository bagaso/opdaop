<?php

namespace App\Http\Controllers\Pages;

use App\Page;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Blade;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Throwable;

class ContentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $page = $request->page;
        $qpage = Page::where('slug_url', $page)->firstorfail();
        if(!$qpage->is_public) {
            $this->middleware(['auth']);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($page = '')
    {
        if($page == '') {
            return redirect(route('index'));
        }
        $page = Page::where('slug_url', $page)->firstorfail();
        return $this->render(Blade::compileString($page->content), []);
    }

    function render($__php, $__data)
    {
        $__data['__env'] = app(\Illuminate\View\Factory::class);
        $obLevel = ob_get_level();
        ob_start();
        extract($__data, EXTR_SKIP);
        try {
            eval('?' . '>' . $__php);
        } catch (Exception $e) {
            while (ob_get_level() > $obLevel) ob_end_clean();
            throw $e;
        } catch (Throwable $e) {
            while (ob_get_level() > $obLevel) ob_end_clean();
            throw new FatalThrowableError($e);
        }
        return ob_get_clean();
    }
}
