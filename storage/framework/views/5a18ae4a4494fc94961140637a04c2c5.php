<?php $__env->startSection('admin'); ?>

<div class="container-fluid mt-5">
  <div class="row">
<div class="col-md-12">
  <div class="white_shd full margin_bottom_30">
     <div class="full graph_head">
        <div class="heading1 margin_0 d-flex">
           <h2>USDT QR</h2>

        </div>
     </div>
     <div class="table_section padding_infor_info">
        <div class="table-responsive-sm">
           <table id="example" class="table table-striped" style="width:100%">
              <thead class="thead-dark">
                 <tr>
                    <th>Sr.No</th>
					 <th>name</th>
                   <th>Wallet Address</th>
                    <th>Image</th>
                    <th>Action</th>

                 </tr>
              </thead>
              <tbody>
                <?php $__currentLoopData = $usdt; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                 <tr>
                     <td><?php echo e($item->id); ?></td>
					  <td><?php echo e($item->name); ?></td>
                      <td><?php echo e($item->wallet_address); ?></td>
                  <td><img src="<?php echo e(URL::asset($item->qr_code )); ?>" width="50 px" height="50 px"></td>
                    <td>
                       <a type="button" class="btn btn-info" data-toggle="modal" data-target="#exampleModalCenter<?php echo e($item->id); ?>" >Update QR</a>
						
							  
				  <div class="modal fade" id="exampleModalCenter<?php echo e($item->id); ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document">
     <div class="modal-content">
       <div class="modal-header">
         <h5 class="modal-title" id="exampleModalLongTitle">Update QR</h5>
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">&times;</span>
         </button>
       </div>
      <form action="<?php echo e(route('usdtqr.update',$item->id)); ?>" method="POST" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
       <div class="modal-body">
         <div class="container-fluid">
           <div class="row">
             <div class="form-group col-md-6">
               <label for="image">Image</label>
               <input type="file" class="form-control" id="image" name="image" placeholder=" ">
             </div>

                           <div class="form-group col-md-6">
               <label for="wallet_address">Wallet Address</label>
               <input type="text" class="form-control" id="wallet_address" name="wallet_address" value="<?php echo e($item->wallet_address); ?>" 
				placeholder=" ">
             </div>

           </div>
         </div>

                   </div>
                    <div class="modal-footer">
         <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
         <button type="submit" class="btn btn-primary">Add</button>
       </div>
       </form>

     </div>
   </div>
 </div>
						
                    </td>

                 </tr>
			
                 <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </tbody>
           </table>
        </div>
     </div>
  </div>
</div>
</div>
</div> 


 
 
 <script>
     $('#myModal').on('shown.bs.modal', function () {
   $('#myInput').trigger('focus')
    })
 </script>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.body.adminmaster', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u853168956/domains/apponrent.com/public_html/trx/resources/views/usdt_qr/usdt_qr.blade.php ENDPATH**/ ?>