<?php
return [
    'accountType'   =>  [
        'customer'      => 1,
        'contractor'    => 2,
        'vendor'        => 3,
        'freelancer'    => 4,
    ],
    'status' => [
        'SP_APPROVED' => 'sp_approved',
        'SP_REJECTED' => 'sp_rejected',
        'CM_REJECTED' => 'customer_rejected',
        'PMT_PENDING' => 'payment_pending',
        'PENDING' => 'pending',
        'ONGOING' => 'ongoing',
        'REJECTED' => 'rejected',
        'COMPLETED' => 'completed',
        'CANCELLED' => 'cancelled',
        'FAILED' => 'failed',
        'ACTIVE' => 'active',
        'OPEN' => 'open',
        'ACCEPTED' => 'accepted',
        'ASSIGNED' => 'assigned',
        'EXPIRED' => 'expired',
    ],
];