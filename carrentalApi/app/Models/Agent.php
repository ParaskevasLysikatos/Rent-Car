<?php

namespace App\Models;

use App\Filters\AgentFilter;
use App\Traits\ModelHasBalancesTrait;
use App\Traits\ModelHasCommentsTrait;
use App\Traits\ModelHasContactsTrait;
use App\Traits\ModelHasDocumentsTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Agent
 *
 * @property int      $id
 * @property string   $created_at
 * @property string   $updated_at
 * @property string   $deleted_at
 * @property string   $name
 * @property float    $commission
 * @property int|null $contact_information_id
 * @property string|null $account_manager
 * @property string|null $referal_by
 * @property string|null $comments
 * @property int|null $booking_source_id
 * @property int|null $company_id
 * @property int|null $parent_agent_id
 * @property int|null $contact_id
 * @property float $balance
 * @property int|null $program_id
 */
class Agent extends Model
{
    use ModelHasDocumentsTrait;
    use ModelHasCommentsTrait;
    use ModelHasContactsTrait;
    use ModelHasBalancesTrait;
    use SoftDeletes;

    protected $fillable = [
        'id',
    ];

    protected $casts = [
        'commission' => 'float',
    ];

    public function scopeFilter(Builder $builder, $request)// v2 filters, see filters folder
    {
        return (new AgentFilter($request))->filter($builder);
    }

    public function getDocumentsPath()
    {
        return 'agensts/agent-' . $this->id . '/';
    }

    public function getInitialFileName()
    {
        return 'agentID';
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function booking_source() {
        return $this->belongsTo(BookingSource::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function contact_information()
    {
        return $this->belongsTo(ContactInformation::class, 'contact_information_id', 'id');
    }

    public function contact_informationEmail(){
        return $this->contact_information->email ?? '';
    }

    public function parent_agent() {
        return $this->belongsTo(Agent::class);
    }

    public function company() {
        return $this->belongsTo(Company::class);
    }

    public function agents() {
        return $this->hasMany(Agent::class, 'parent_agent_id', 'id');
    }

    public function program() {
        return $this->belongsTo(Program::class);
    }

    public function sub_contacts() {
        return $this->morphToMany(Contact::class, 'agent_contact');
    }

    public function getSubaccountsAttribute()
    {
        $agents = Agent::where('parent_agent_id', $this->id)->get();
        $contacts = Contact::where('agent_id', $this->id)->get();
        foreach ($agents as $agent) {
            $agent->model = Agent::class;
            $agent->account_id = $agent->id;
        }
        foreach ($contacts as $contact) {
            $contact->model = Contact::class;
            $contact->name = $contact->full_name;
            $contact->account_id = $contact->id;
        }
        $subaccounts = $agents->concat($contacts);
        return $subaccounts;
    }
}
