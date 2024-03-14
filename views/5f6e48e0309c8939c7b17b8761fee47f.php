<b>Новый заказ</b>

<?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<b><?php echo e(ucfirst($key)); ?></b>: <?php echo e($value); ?>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php /**PATH D:\BatalovVA\phpstorm\kwork\clean_php_bot_notify_to_group\views/message.blade.php ENDPATH**/ ?>