<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BidCreation_Creation extends Model
{
    use HasFactory;
    protected $fillable = ['bidno',
    'customername',
    'bidcall',
    'tenderid',
    'tenderinvtauth',
    'tenderref',
    'state',
    'ulb',
    'TenderDescription',
    'NITdate',
    'submissiondate',
    'quality',
    'unit',
    'tenderevalutionsysytem',
    'projectperioddate1',
    'projectperioddate2',
    'estprojectvalue',
    'tenderfeevalue',
    'priceperunit',
    'emdmode',
    'emdamt',
    'dumpsiter',
    'prebiddate',
    'EMD',
    'location'];
}
