<?php $__env->startSection('admin'); ?>

<?php
    use Illuminate\Support\Facades\DB;
    $result = DB::select("
        SELECT
            COUNT(CASE WHEN account_type = 1 THEN 1 END) AS demo_user,
            COUNT(CASE WHEN illegal_count > 0 THEN 1 END) AS illegal_better
        FROM users
    ");
    $demoUser = $result[0]->demo_user;
    $illegalBetter = $result[0]->illegal_better;
?>

<style>
    .card {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        border: none;
        border-left: 4px solid;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }
    .border-left-primary { border-color: #007bff; }
    .border-left-success { border-color: #28a745; }
    .border-left-info    { border-color: #17a2b8; }
    .border-left-warning { border-color: #ffc107; }
    .border-left-danger  { border-color: #dc3545; }
    .border-left-dark    { border-color: #343a40; }
    .text-purple         { color: #6f42c1!important; }
</style>

<div class="midde_cont">
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col">
                <h2 class="fw-bold">Admin Dashboard</h2>
            </div>
        </div>

        
        <form action="<?php echo e(route('dashboard')); ?>" method="get" class="row g-3 mb-4">
            <?php echo csrf_field(); ?>
            <div class="col-md-3">
                <input type="date" class="form-control" name="start_date" placeholder="Start Date">
            </div>
            <div class="col-md-3">
                <input type="date" class="form-control" name="end_date" placeholder="End Date">
            </div>
            <div class="col-md-3">
                <button class="btn btn-success">Search</button>
                <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-secondary">Reset</a>
            </div>
        </form>

        
        <div class="row g-4">
            
            <?php $__currentLoopData = [
                ['icon'=>'fa-user',             'label'=>'Total Player',       'value'=>$users[0]->totaluser,       'color'=>'primary'],
                ['icon'=>'fa-user-check',       'label'=>'Active Player',      'value'=>$users[0]->activeuser,      'color'=>'success'],
                ['icon'=>'fa-user-plus',        'label'=>'Today User',         'value'=>$users[0]->todayuser,       'color'=>'info'],
                ['icon'=>'fa-chart-line',       'label'=>'Today Turnover',     'value'=>$users[0]->todayturnover,   'color'=>'warning'],
                ['icon'=>'fa-wallet',           'label'=>'Total Turnover',     'value'=>$users[0]->total_turnover,  'color'=>'dark'],
                ['icon'=>'fa-university',       'label'=>'Total Deposit',      'value'=>$users[0]->totaldeposit,    'color'=>'info'],
                ['icon'=>'fa-money-bill-wave',  'label'=>'Today Deposit',      'value'=>$users[0]->tdeposit,        'color'=>'success'],
                ['icon'=>'fa-hand-holding-usd', 'label'=>'Total Withdrawal',   'value'=>$users[0]->totalwithdraw,   'color'=>'danger'],
                ['icon'=>'fa-cash-register',    'label'=>'Today Withdrawal',   'value'=>$users[0]->tamount,         'color'=>'warning'],
                ['icon'=>'fa-comments',         'label'=>'Feedbacks',          'value'=>$users[0]->totalfeedback,   'color'=>'info'],
                ['icon'=>'fa-gamepad',          'label'=>'Total Games',        'value'=>$users[0]->totalgames,      'color'=>'purple'],
                ['icon'=>'fa-percent',          'label'=>'Total Commission',   'value'=>$users[0]->commissions,     'color'=>'dark'],
              
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-6 col-lg-3">
                    <div class="card border-left-<?php echo e($card['color']); ?> shadow h-100 py-2">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3">
                                <i class="fa <?php echo e($card['icon']); ?> fa-2x text-<?php echo e($card['color']); ?> <?php echo e($card['color']=='purple'?'text-purple':''); ?>"></i>
                            </div>
                            <div>
                                <div class="text-muted"><?php echo e($card['label']); ?></div>
                                <h4 class="fw-bold mb-0"><?php echo e($card['value']); ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.body.adminmaster', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u853168956/domains/apponrent.com/public_html/trx/resources/views/admin/index.blade.php ENDPATH**/ ?>