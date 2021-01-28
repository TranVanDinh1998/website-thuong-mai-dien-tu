<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;
use App\Product;
use App\Contact;

class ContactController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function index(Request $request)
    {
        // contacts
        $contacts = contact::notDelete()->sortId($request)->status($request)->orderByDesc('create_date');
        $count_contact = 0;
        $view = 0;
        $count_contact = $contacts->count();
        if ($request->has('view')) {
            $view = $request->view;
        } else {
            $view = 10;
        }
        $contacts = $contacts->paginate($view);
        // filter
        $sort_id = $request->sort_id;
        $status = $request->status;
        // search
        $search = $request->search;
        // user
        $user = Auth::guard('admin')->user();
        return view('admin.contact.index', [
            // contacts
            'contacts' => $contacts,
            'count_contact' => $count_contact,
            'view' => $view,
            // filter
            'sort_id' => $sort_id,
            'status' => $status,
            //
            // search
            'search' => $search,
            'current_user' => $user,
        ]);
    }

    public function doRead($id)
    {
        $contact = contact::find($id);
        $contact->is_read = 1;
        if ($contact->save()) {
            return back()->with('success', 'Contact #' . $contact->id . ' has been read.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function doUnread($id)
    {
        $contact = contact::find($id);
        $contact->is_read = 0;
        if ($contact->save()) {
            return back()->with('success', 'Contact #' . $contact->id . ' has been read.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }


    public function detail($id)
    {
        // user
        $user = Auth::guard('admin')->user();
        $contact =  Contact::find($id);
        return view('admin.contact.detail', [
            'contact' => $contact,
            //
            'current_user' => $user,
        ]);
    }

    public function doRemove($id)
    {
        $contact = Contact::find($id);
        if ($contact->is_read == 0) {
            return back()->with('error','Contact #'.$contact->id.' hasn\'t been read yet.');
        }
        else {
            $contact->is_deleted = 1;
            if ($contact->save()) {
                return back()->with('success', 'Contact #' . $contact->id . ' has been removed.');
            } else {
                return back()->with('error', 'Error occurred!');
            }
        }
    }

    public function recycle(Request $request)
    {
        // contacts
        $contacts = contact::softDelete()->sortId($request)->status($request);
        $count_contact = 0;
        $view = 0;
        $count_contact = $contacts->count();
        if ($request->has('view')) {
            $view = $request->view;
        } else {
            $view = 10;
        }
        $contacts = $contacts->paginate($view);
        // filter
        $sort_id = $request->sort_id;
        $status = $request->status;
        // search
        $search = $request->search;
        // user
        $user = Auth::guard('admin')->user();
        return view('admin.contact.recycle', [
            // contacts
            'contacts' => $contacts,
            'count_contact' => $count_contact,
            'view' => $view,
            // filter
            'sort_id' => $sort_id,
            'status' => $status,
            //
            // search
            'search' => $search,
            'current_user' => $user,
        ]);
    }

    public function doRestore($id)
    {
        $contact = contact::find($id);
        $contact->is_deleted = 0;
        if ($contact->save()) {
            return back()->with('success', 'Contact #' . $contact->id . ' has been restored.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function doDelete($id)
    {
        $contact = Contact::find($id);
        if ($contact->is_read == 0) {
            return back()->with('error','Contact #'.$contact->id.' hasn\'t been read yet.');
        }
        else {
            $contact->is_deleted = 1;
            if ($contact->forceDelete()) {
                return back()->with('success', 'Contact #' . $contact->id . ' has been deleted.');
            } else {
                return back()->with('error', 'Error occurred!');
            }
        }
    }
    public function bulk_action(Request $request)
    {
        if ($request->has('bulk_action')) {
            if ($request->has('contact_id_list')) {
                $message = null;
                $error = null;
                switch ($request->bulk_action) {
                    case 0: // read
                        $message = 'Contact ';
                        foreach ($request->contact_id_list as $contact_id) {
                            $contact = null;
                            $contact = contact::find($contact_id);
                            $contact->is_read = 1;
                            if ($contact->save()) {
                                $message .= ' #' . $contact->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred while marking contact #' . $contact->id.' as read message.');
                            }
                        }
                        $message .= 'have been marked as read message(s).';
                        return back()->with('success', $message);
                        break;
                    case 1: // unread
                        $message = 'Contact ';
                        foreach ($request->contact_id_list as $contact_id) {
                            $contact = null;
                            $contact = contact::find($contact_id);
                            $contact->is_read = 0;
                            if ($contact->save()) {
                                $message .= ' #' . $contact->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred while marking contact #' . $contact->id.' as unread message.');
                            }
                        }
                        $message .= 'have been marked as unread message(s).';
                        return back()->with('success', $message);
                        break;
                    case 2: // remove
                        $message = 'Contact';
                        foreach ($request->contact_id_list as $contact_id) {
                            $contact = null;
                            $contact = contact::find($contact_id);
                            if ($contact->is_read == 1) {
                                $contact->is_deleted = 1;
                                if ($contact->save()) {
                                    $message .= ' #' . $contact->id . ', ';
                                } else {
                                    return back()->with('error', 'Error occurred while removing contact #' . $contact->id);
                                }
                            }
                            else {
                                $error .= 'Contact #'.$contact->id.' hasn\'t been read yet.';
                            }
                        }
                        $message .= 'have been removed.';
                        break;
                    case 3: // restore
                        $message = 'contact ';
                        foreach ($request->contact_id_list as $contact_id) {
                            $contact = null;
                            $contact = contact::find($contact_id);
                            $contact->is_deleted = 0;
                            if ($contact->save()) {
                                $message .= ' #' . $contact->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred while restoring contact #' . $contact->id);
                            }
                        }
                        $message .= 'have been restored.';
                        return back()->with('success', $message);
                        break;
                    case 4: // delete
                        $message = 'Contact';
                        foreach ($request->contact_id_list as $contact_id) {
                            $contact = null;
                            $contact = contact::find($contact_id);
                            if ($contact->is_read == 1) {
                                if ($contact->forceDelete()) {
                                    $message .= ' #' . $contact->id . ', ';
                                } else {
                                    return back()->with('error', 'Error occurred while deleting contact #' . $contact->id);
                                }
                            }
                            else {
                                $error .= 'Contact #'.$contact->id.' hasn\'t been read yet.';
                            }
                        }
                        $message .= 'have been deleted.';
                        break;
                }
                if ($error != null) {
                    return back()->with('success', $message)->with('error', $error);
                }
                return back()->with('success', $message);
            } else {
                return back()->with('error', 'Please select contacts to take action!');
            }
        } else {
            return back()->with('error', 'Please select an action!');
        }
    }
}
