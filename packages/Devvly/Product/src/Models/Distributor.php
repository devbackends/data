<?php

namespace Devvly\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Devvly\Product\Contracts\Distributor as Contract;


/**
 * Devvly\Product\Models\Distributor
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $host
 * @property string|null $contact_name
 * @property string|null $contact_email
 * @property string|null $contact_number
 * @property string|null $contact_fax
 * @property string|null $country
 * @property string|null $state
 * @property string|null $city
 * @property string|null $street
 * @property string|null $postcode
 * @property int $priority
 * @property float|null $latitude
 * @property float|null $longitude
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @mixin \Eloquent
 */
class Distributor extends Model implements Contract
{
  /**
   * @var string
   */
  protected $table = 'distributors';
  protected $fillable = ['name','description','host','contact_name','contact_email','contact_number','contact_fax','country','state','city','street','postcode','priority','latitude','longitude','status'];

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function inventories()
  {
    return $this->hasMany(Inventory::class);
  }
}
