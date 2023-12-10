<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;

use App\Models\Blog\BlogCategory;
use App\Models\Blog\BlogSubcategory;
use App\Models\Blog\BlogSubSubcategory;

use App\Models\Blog\BlogPage;

use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Session;

class BlogPageController extends Controller
{
    public function index($slug)
    {
        $pages = BlogPage::all();
        $pageContent = BlogPage::where('slug', $slug)->firstOrFail();

        return view('frontend.blog.skeleton.body', ['pages' => $pages, 'pageContent' => $pageContent]);
    }
    
    public function privacy()
    {
        $page = BlogPage::where('slug', 'privacy-policy')->firstOrFail();
        
        return view('frontend.blog.privacy-policy', ['page' => $page]);
    }
    
    public function terms()
    {
        $page = BlogPage::where('slug', 'terms-of-service')->firstOrFail();
        
        return view('frontend.blog.terms-of-service', ['page' => $page]);
    }
    
    public function license()
    {
        $page = BlogPage::where('slug', 'license')->firstOrFail();
        
        return view('frontend.blog.license', ['page' => $page]);
    }
    
    public function error404()
    {
        $page = BlogPage::where('slug', '404')->firstOrFail();
        
        return view('frontend.blog.404', ['page' => $page]);
    }

    public function create(Request $request)
    {
        $categories = BlogCategory::all();
        $subcategories = BlogSubcategory::all();
        $sub_subcategories = BlogSubSubcategory::all();

        return view('backend.blog.page.new-page', [
            'categories' => $categories, 
            'subcategories' => $subcategories, 
            'sub_subcategories' => $sub_subcategories,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        // $request->validate([
        //     'email' => 'email',
        // ]);

        // dd($request);

        $page = BlogPage::create([
            'name' => $request->name,
            'title' => $request->title,
            'slug' => $request->slug,
            'tags' => $request->tags,
            'category_name' => $request->category_name,
            'subcategory_name' => $request->subcategory_name,
            'sub_subcategory_name' => $request->sub_subcategory_name,
            'short_description' => $request->short_description,
            'long_description' => $request->long_description,
            'youtube_iframe' => $request->youtube_iframe,
            'header_content' => $request->header_content,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'facebook_meta_title' => $request->facebook_meta_title,
            'facebook_meta_description' => $request->facebook_meta_description,
            'twitter_meta_title' => $request->twitter_meta_title,
            'twitter_meta_description' => $request->twitter_meta_description,
            'thumb_alt_text' => $request->thumb_alt_text,
            'breadcrumb_alt_text' => $request->breadcrumb_alt_text,
            'cover_alt_text' => $request->cover_alt_text,
            'og_img_alt_text' => $request->og_img_alt_text,
            'status' => $request->status,
            'comment' => $request->comment,
        ]);

        $page->save();

        if ($request->hasFile('thumb')) {
            $thumb = $request->file('thumb')->getClientOriginalName();
            $request->file('thumb')->move(public_path('blog/page/image/thumb'), $thumb);
            $page->thumb = $thumb;
        }

        if ($request->hasFile('breadcrumb_image')) {
            $breadcrumbImage = $request->file('breadcrumb_image')->getClientOriginalName();
            $request->file('breadcrumb_image')->move(public_path('blog/page/image/breadcrumb'), $breadcrumbImage);
            $page->breadcrumb_image = $breadcrumbImage;
        }

        if ($request->hasFile('cover_image')) {
            $coverImage = $request->file('cover_image')->getClientOriginalName();
            $request->file('cover_image')->move(public_path('blog/page/image/cover'), $coverImage);
            $page->cover_image = $coverImage;
        }

        if ($request->hasFile('og_image')) {
            $oGImage = $request->file('og_image')->getClientOriginalName();
            $request->file('og_image')->move(public_path('blog/page/image/og'), $oGImage);
            $page->og_image = $oGImage;
        }

        if ($request->hasFile('thumb') || $request->hasFile('breadcrumb_image') || $request->hasFile('cover_image') || $request->hasFile('og_image')) {
            $page->save();
        }

        Session::flash('message', __('New Page Successfully Created!'));
        
        return redirect(RouteServiceProvider::BlogPage);
    }

    public function show(Request $request)
    {            
        $pages = BlogPage::all();
        
        return view('backend.blog.page.manage-pages', ['pages' => $pages]);
    }

    public function edit($id)
    {
        $page = BlogPage::findOrFail($id);
        $categories = BlogCategory::all();
        $subcategories = BlogSubcategory::all();
        $sub_subcategories = BlogSubSubcategory::all();
        
        return view('backend.blog.page.edit-page', [
            'page' => $page,
            'categories' => $categories,
            'subcategories' => $subcategories,
            'sub_subcategories' => $sub_subcategories,
        ]);
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $page = Blogpage::find($id);

        if ($page) {
            $thumb = $request->file('thumb');

            if ($thumb) {
                $validatedData = $request->validate([
                    // 'thumb' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                ]);

                $thumbName = $request->thumb->getClientOriginalName();
                $request->thumb->move(public_path('blog/page/image/thumb'), $thumbName);

                $page->thumb = $thumbName;
            }

            $breadcrumb = $request->file('breadcrumb_image');

            if ($breadcrumb) {
                $validatedData = $request->validate([
                    // 'breadcrumb_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                ]);

                $breadcrumbName = $request->breadcrumb_image->getClientOriginalName();
                $request->breadcrumb_image->move(public_path('blog/page/image/breadcrumb'), $breadcrumbName);

                $page->breadcrumb_image = $breadcrumbName;
            }

            $cover = $request->file('cover_image');

            if ($cover) {
                $validatedData = $request->validate([
                    // 'cover_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                ]);

                $coverName = $request->cover_image->getClientOriginalName();
                $request->cover_image->move(public_path('blog/page/image/cover'), $coverName);

                $page->cover_image = $coverName;
            }

            $og = $request->file('og_image');

            if ($og) {
                $validatedData = $request->validate([
                    // 'og_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                ]);

                $ogImageName = $request->og_image->getClientOriginalName();
                $request->og_image->move(public_path('blog/page/image/og'), $ogImageName);

                $page->og_image = $ogImageName;
            }

            // Update other fields of the request
            $page->name = $request->input('name');
            $page->title = $request->input('title');
            $page->slug = $request->input('slug');            
            $page->tags = $request->input('tags');
            $page->category_name = $request->input('category_name');
            $page->subcategory_name = $request->input('subcategory_name');
            $page->sub_subcategory_name = $request->input('sub_subcategory_name');
            $page->short_description = $request->input('short_description');
            $page->long_description = $request->input('long_description');
            $page->youtube_iframe = $request->input('youtube_iframe');
            $page->header_content = $request->input('header_content');
            $page->meta_title = $request->input('meta_title');
            $page->meta_description = $request->input('meta_description');
            $page->facebook_meta_title = $request->input('facebook_meta_title');
            $page->facebook_meta_description = $request->input('facebook_meta_description');
            $page->twitter_meta_title = $request->input('twitter_meta_title');
            $page->twitter_meta_description = $request->input('twitter_meta_description');
            $page->thumb_alt_text = $request->input('thumb_alt_text');
            $page->breadcrumb_alt_text = $request->input('breadcrumb_alt_text');
            $page->cover_alt_text = $request->input('cover_alt_text');
            $page->og_img_alt_text = $request->input('og_img_alt_text');

            if (!is_null($request->input('status'))) {
                $page->status = $request->input('status');
            }
            
            $page->comment = $request->input('comment');

            // Save the changes
            $page->save();

            // Perform any additional actions or redirect as needed
        } else {
            // Handle the case when the record doesn't exist
            Session::flash('update', __('There is a problem!'));

            return redirect()->back();
        }

        Session::flash('update', __('Page Successfully Updated!'));
        
        return redirect(RouteServiceProvider::BlogPage);
    }

    public function destroy(Request $request, $id)
    {
        BlogPage::where('id',$id)->delete();

        Session::flash('delete', __('Congratulations! The page deletion operation has been successfully executed.'));
        
        return back();
    }
}