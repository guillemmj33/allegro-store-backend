<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api', ['except' => ['index', 'show']]);
  }

  public function index()
  {
    try {
      $items = Item::all();
      return response()->json([
        'status' => 'success',
        'items' => $items,
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 'error',
        'message' => 'Unable to fetch items',
        'error_code' => 500,
      ], 500);
    }
  }

  public function store(Request $request)
  {
    $request->validate([
      'title' => 'required|string|max:255',
      'description' => 'required|string|max:255',
      'image' => 'required',
      'category' => 'required|string|max:255',
      'price' => 'required|numeric|min:0',
      'location' => 'required|max:255',
    ]);

    // Handle image
    $image = $request->file('image');
    $imageName = time() . '.' . $image->getClientOriginalExtension();
    $imagePath = 'storage/images/';
    $image->move(public_path($imagePath), $imageName);

    $item = Item::create([
      'user_id' => auth()->id(),
      'title' => $request->title,
      'description' => $request->description,
      'image' => $imagePath . $imageName,
      'category' => $request->category,
      'price' => $request->price,
      'location' => $request->location,
    ]);

    try {
      return response()->json([
        'status' => 'success',
        'message' => 'Item created successfully',
        'item' => $item,
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 'error',
        'message' => 'Unable to create item',
        'error_code' => 500,
      ], 500);
    }
  }

  public function show($id)
  {
    try {
      $item = Item::find($id);
      return response()->json([
        'status' => 'success',
        'item' => $item,
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 'error',
        'message' => 'Unable to fetch item',
        'error_code' => 500,
      ], 500);
    }
  }

  public function update(Request $request, $id)
  {
    $request->validate([
      'title' => 'required|string|max:255',
      'description' => 'required|string|max:255',
      'image' => 'required',
      'category' => 'required|string|max:255',
      'price' => 'required|numeric|min:0',
      'location' => 'required|max:255',
    ]);

    $item = Item::find($id);
    $item->user_id = auth()->id();
    $item->title = $request->title;
    $item->description = $request->description;
    $item->image = $request->image;
    $item->category = $request->category;
    $item->price = $request->price;
    $item->location = $request->location;
    $item->save();

    try {
      return response()->json([
        'status' => 'success',
        'message' => 'Item updated successfully',
        'item' => $item,
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 'error',
        'message' => 'Unable to update item',
        'error_code' => 500,
      ], 500);
    }
  }

  public function destroy($id)
  {
    $item = Item::find($id);
    $item->delete();

    try {
      return response()->json([
        'status' => 'success',
        'message' => 'Item deleted successfully',
        'item' => $item,
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'status' => 'error',
        'message' => 'Unable to delete item',
        'error_code' => 500,
      ], 500);
    }
  }
}
