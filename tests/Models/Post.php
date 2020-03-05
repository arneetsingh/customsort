<?php

namespace Arni\CustomSort\Tests\Models;

use Arni\CustomSort\Traits\HasCustomSortTrait;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasCustomSortTrait;
}
