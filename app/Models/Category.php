<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name','parent_id','name','image'];

    protected $hidden = ['updated_at','image'];

    protected $appends = ['image_url'];

    public function rules()
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'image' => 'nullable|image',
            'parent_id' => 'nullable|exists:categories,id'
        ];
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return 'https://www.google.com/url?sa=i&url=https%3A%2F%2Fwordpress.org%2Fplugins%2Fno-category-base-wpml%2F&psig=AOvVaw2RPCcKPnii4VMYHCAD1paf&ust=1723321577119000&source=images&cd=vfe&opi=89978449&ved=0CBEQjRxqFwoTCPj4w6vf6IcDFQAAAAAdAAAAABAE';
        }
        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;

        }
        return asset('storage/' . $this->image);

    }
}
