<?php $__env->startSection('admin'); ?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
  body {
    background: #f5f5f5;
  }

  .container-fluid {
    max-width: 1200px;
    margin: 0 auto;
  }

  /* Mode tab group styling */
  .mode-tab-group {
      background-color:#4d4d4c ;
    display: flex;
    border-radius: 12px;
    overflow: hidden;
    margin: 20px 0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  }
  .mode-tab-group a {
    flex: 1;
    text-align: center;
    padding: 15px 0;
    text-decoration: none;
    color: #fff;
    background-color: #4d4d4c;
    transition: background 0.3s ease;
  }
  .mode-tab-group a.active {
    background: linear-gradient(45deg, #F0D58D, #CA9C49);
    color: #000;
    font-weight: bold;
    box-shadow: inset 0 -4px 0 rgba(202,156,73,0.8);
  }
  .mode-tab-group img {
    width: 44px;
    height: 44px;
    margin: 0 auto 6px;
  }
  .mode-tab-group span {
    display: block;
    font-size: 0.95rem;
    font-weight: 600;
    text-shadow: 0 1px 2px rgba(0,0,0,0.3);
  }

  .card-modern {
    background: #fff;
    border-radius: .75rem;
    transition: transform .3s, box-shadow .3s;
  }
  .card-modern:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.1);
  }

  .section-bg {
    background: #f8f9fa;
    padding: 2rem;
    border-radius: .75rem;
  }

  /* Copy icon */
  .copy-btn {
    cursor: pointer;
    color: black;
    font-size: 1.4rem;
    margin-left: 12px;
    background: transparent;
    border: none;
    transition: color 0.2s ease;
  }
  .copy-btn:hover {
    color: #000;
  }

  /* Result icons container */
  .results-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
  }

  /* Each result + amount box wrapper */
  .result-wrapper {
    width: 18%; /* approx 5 per row with some gap */
    min-width: 100px;
    text-align: center;
  }

  /* Result icons */
  .data-count {
    display: inline-block;
    border-radius: 50%;
    width: 64px;
    height: 64px;
    background: #fff;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    margin: 0 auto;
    cursor: pointer;
    position: relative;
    transition: transform 0.2s ease, box-shadow 0.3s ease;
  }
  .data-count:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
  }
  .data-count img {
    width: 40px;
    height: 40px;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
  }

  /* Amount box */
  .amount-box {
    background: #fff;
    border-radius: 8px;
    padding: 10px 0;
    margin-top: 8px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    font-weight: 600;
    font-size: 1rem;
  }

  /* Submit button */
  .btn-submit {
    background-color: #28a745;
    color: #fff;
    border-radius: 50px;
    padding: 10px 28px;
    font-weight: 600;
    border: none;
    display: inline-flex;
    align-items: center;
    transition: background 0.3s ease, box-shadow 0.3s ease;
  }
  .btn-submit:hover {
    background-color: #218838;
    box-shadow: 0 6px 18px rgba(40,167,69,0.4);
  }
  .btn-submit i {
    margin-left: 8px;
  }

  /* Responsive tweaks */
  @media (max-width: 768px) {
    .result-wrapper {
      width: 30%; /* 3 per row */
    }
  }
  @media (max-width: 480px) {
    .result-wrapper {
      width: 45%; /* 2 per row */
    }
  }
</style>

<div class="container-fluid py-5">
    
     
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-success shadow">
                <div class="card-body">
                    <h5>Total Admin Profit</h5>
                    <h3>₹<?php echo e($total_admin_profit ?? 0); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger shadow">
                <div class="card-body">
                    <h5>Total User Profit</h5>
                    <h3>₹<?php echo e($total_user_profit ?? 0); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info shadow">
                <div class="card-body">
                    <h5>Today Admin Profit</h5>
                    <h3>₹<?php echo e($today_admin_profit ?? 0); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning shadow">
                <div class="card-body">
                    <h5>Today User Profit</h5>
                    <h3>₹<?php echo e($today_user_profit ?? 0); ?></h3>
                </div>
            </div>
        </div>
    </div>
    
  <div class="row d-flex justify-content-between align-items-center">
    <!-- Left Side -->
    <div class="col-md-6">
        <div class="game-header fw-bold mb-0" id="gmsno"style="font-size:25px;">
            <!--Period No - <?php echo e($bets[0]->games_no ?? '-'); ?>-->
        </div>
    </div>

    <!-- Right Side -->
    <div class="col-md-6 text-end">
        <b id="users_playing_block" style="font-weight: bold; font-size: 22px;">
            Total Users Playing - <span><?php echo e($total_users_playing ?? 0); ?></span>
        </b>
    </div>
</div>


  <?php
    $modes = ['Wingo 30 Second', 'Wingo 1 Minute', 'Wingo 3 Minute', 'Wingo 5 Minute'];
    $gameModes = DB::table('game_settings')
      ->whereIn('name', $modes)
      ->orderByRaw("FIELD(name, '".implode("','", $modes)."')")
      ->get();
  ?>

  
  <div class="mode-tab-group">
    <?php $__currentLoopData = $gameModes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mode): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <?php
        $isActive = request()->is('colour_prediction/'.$mode->id);
        $img = $isActive ? 'https://bigcashino.123ace.in/wingo/redWatch.png' : 'https://bigcashino.123ace.in/wingo/grayWatch.png';
      ?>
      <a style="border-radius:12px;" href="<?php echo e(route('colour_prediction', $mode->id)); ?>" class="<?php echo e($isActive ? 'active' : ''); ?>">
        <img src="<?php echo e($img); ?>" alt="Mode Icon">
        <span><?php echo e($mode->name); ?></span>
      </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>

  <?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
      <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  
  <div class="card-modern shadow-sm mb-5">
    <div class="card-header text-white" style="background: linear-gradient(60deg,#007bff,#6610f2);">
      <h5><i class="fas fa-chart-bar me-2"></i>Game Results </h5>
    </div>
    <div class="section-bg">
      <!--<div class="d-flex justify-content-center align-items-center mb-4">-->
      <!--  <h4 id="gmsno" class="fw-bold mb-0"></h4>-->
      <!--  <button class="copy-btn" onclick="copyPeriodNo()" title="Copy Period No">-->
      <!--    <i style="color:black" class="fas fa-copy"></i>-->
      <!--  </button>-->
      <!--</div>-->

      <div class="results-container" id="results-container">
        <?php $__currentLoopData = $bets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="result-wrapper">
            <div class="data-count" data-number="<?php echo e($key); ?>" data-gameid="<?php echo e($gameid); ?>">
              <img src="https://bigcashino.123ace.in/wingo/<?php echo e($key); ?>.png" alt="<?php echo e($key); ?>">
            </div>
            <div class="amount-box" id="amount-<?php echo e($key); ?>">-</div>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    </div>
  </div>

  
  <div class="card-modern shadow-sm">
    <!--<div class="card-header text-white" style="background: linear-gradient(60deg,#20c997,#0dcaf0);">-->
    <!--  <h5><i class="fas fa-hourglass-half me-2"></i>Schedule Future Result</h5>-->
      
    <!--   <button class="btn btn-light btn-sm rounded-pill" onclick="copyPeriodNo()" title="Copy Period No">-->
    <!--            <i class="fas fa-copy me-1" style="color:black;"></i> Copy Period:-->
    <!--            <span id="copy-period"><?php echo e($bets[0]->games_no ?? '-'); ?></span>-->
    <!--        </button>-->
    <!--</div>-->
    <div class="card-header  d-flex justify-content-between align-items-center" style="background: linear-gradient(60deg,#20c997,#0dcaf0);">
            <h5 class="mb-0"><i class="fas fa-hourglass-half me-2"></i>Schedule Future Result</h5>
            <!-- Display the button -->
            <button class="btn btn-light btn-sm rounded-pill" onclick="copyPeriodNo()" title="Copy Period No">
                <i class="fas fa-copy me-1" style="color:black;"></i> Copy Period:
                <span id="copy-period"><?php echo e($bets[0]->games_no ?? '-'); ?></span>
            </button>
        </div>
       
    <div class="section-bg">
      <form method="post" action="<?php echo e(route('future_result.store')); ?>" class="row g-3 needs-validation" novalidate>
        <?php echo csrf_field(); ?>
        <input type="hidden" name="game_id" value="<?php echo e($gameid); ?>">
        <div class="col-sm-5">
          <label><i class="fas fa-calendar-alt me-1"></i>Future Period</label>
          <input type="text" name="game_no" class="form-control rounded-pill" placeholder="Game no" required>
        </div>
        <div class="col-sm-5">
          <label><i class="fas fa-sort-numeric-up-alt me-1"></i>Result</label>
          <select name="number" class="form-select rounded-pill" required>
            <option value="">Select</option>
            <?php for($i=0;$i<=9;$i++): ?>
              <option value="<?php echo e($i); ?>"><?php echo e($i); ?></option>
            <?php endfor; ?>
          </select>
        </div>
        <div class="col-auto d-flex align-items-end">
          <button type="submit" class="btn-submit">
            Submit <i class="fas fa-check"></i>
          </button>
        </div>
      </form>
    </div>
  </div>
  
    
    <div class="d-flex justify-content-start gap-3 mb-3 mt-4">
        <button class="btn btn-primary toggle-btn" data-target="future">Future Predictions</button>
        <button class="btn btn-secondary toggle-btn" data-target="user">User Bets</button>
    </div>

    
    <div id="table-future" class="toggle-table">
        <div class="card shadow">
            <div class="card-header bg-info text-white"><strong>Future Prediction List</strong></div>
            <div class="card-body table-responsive">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr><th>ID</th><th>Period No</th><th>Predicted Number</th><th>Result</th><th>Created</th><th>Updated</th></tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $futurePredictions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prediction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($prediction->id); ?></td>
                                <td><?php echo e($prediction->gamesno); ?></td>
                                <td><?php echo e($prediction->predicted_number); ?></td>
                                <td><?php echo $prediction->result_number === 'pending' ? '<span class="badge bg-warning text-dark">Pending</span>' : '<span class="badge bg-success">'.$prediction->result_number.'</span>'; ?></td>
                                <td><?php echo e($prediction->created_at); ?></td>
                                <td><?php echo e($prediction->updated_at); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="6" class="text-center">No predictions found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    
    <div id="table-user" class="toggle-table d-none">
        <div class="card shadow">
            <div class="card-header bg-warning text-dark"><strong>User Bet List</strong></div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>User ID</th>
                            <th>Period Number</th>
                            <th>Game ID</th>
                            <th>Amount</th>
                            <th>Win Number</th>
                            <th>Win Amount</th>
                            <th>Status</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $userBets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($bet->id); ?></td>
                                <td><?php echo e($bet->userid); ?></td>
                                <td><?php echo e($bet->games_no); ?></td>
                                <td><?php echo e($bet->game_id); ?></td>
                                <td><?php echo e($bet->amount); ?></td>
                                <td><?php echo e($bet->win_number); ?></td>
                                <td><?php echo e($bet->win_amount); ?></td>
                                <td><?php echo e($bet->status); ?></td>
                                <td><?php echo e($bet->created_at); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="9" class="text-center">No user bets found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                
                <!--<?php echo e($userBets->links('pagination::bootstrap-5')); ?>-->
                
<?php echo e($userBets->appends(['tab' => 'user'])->links('pagination::bootstrap-5')); ?>


            </div>
        </div>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  function copyPeriodNo() {
    const txt = $('#gmsno').text().replace('Period No – ', '').trim();
    if (txt) {
      navigator.clipboard.writeText(txt).then(() => alert('Copied: ' + txt));
    }
  }

  function fetchData() {
    fetch(`/fetch/<?php echo e($gameid); ?>`)
      .then(res => res.json())
      .then(d => {
        $('#gmsno').text('Period No – ' + (d.bets[0]?.games_no ?? '—'));

        // Update amount boxes
        d.bets.forEach(i => {
          $(`#amount-${i.number}`).text(i.amount ?? '-');
        });
      });
  }
    function updateBets(bets) {
        let currentGameNo = '';
        $('#card-area .amount-box').each(function () {
            $(this).text('₹0');
        });

        bets.forEach(item => {
            $(`#amount-${item.number}`).text(`₹${item.amount}`);
            currentGameNo = item.games_no;
        });

        $('#gmsno').html(`<div class="game-header">Period No - ${currentGameNo}</div>`);
        $('#games_no').val(currentGameNo);
    }

    function refreshData() {
        fetchData();
        setInterval(fetchData, 5000);
    }
    
    $(document).ready(function () {
    refreshData();

    $('.clickable-card').on('click', function () {
        const number = $(this).data('number');
        const game_id = $('#game_id').val();
        const game_no = $('#games_no').val();

        $.ajax({
            url: "<?php echo e(route('colour_prediction.store')); ?>",
            method: 'POST',
            data: {
                _token: "<?php echo e(csrf_token()); ?>",
                number: number,
                game_id: game_id,
                game_no: game_no
            },
            success: function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Result Submitted!',
                    text: 'Your result was added successfully.',
                    timer: 1500,
                    showConfirmButton: false
                });
                fetchData();
            },
            error: function (xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong while submitting!',
                });
                console.error(xhr.responseText);
            }
        });
    });

    $('.toggle-btn').on('click', function () {
        let target = $(this).data('target');
        $('.toggle-table').addClass('d-none');
        $('#table-' + target).removeClass('d-none');

        // Add tab to URL without reload
        const url = new URL(window.location.href);
        url.searchParams.set('tab', target);
        window.history.replaceState(null, '', url);
    });

    // 🔁 Read tab from URL on page load
    let tabParam = new URLSearchParams(window.location.search).get('tab');
    if (tabParam === 'user') {
        $('.toggle-table').addClass('d-none');
        $('#table-user').removeClass('d-none');
    } else {
        $('.toggle-table').addClass('d-none');
        $('#table-future').removeClass('d-none');
    }
});


  $(function() {
    fetchData();
    setInterval(fetchData, 5000);
    setInterval(() => location.reload(), 60000);

    // Submit result on icon click
    $(document).on('click', '.data-count', function() {
      const num = $(this).data('number');
      const gid = $(this).data('gameid');
      const gno = $('#gmsno').text().match(/\d+/)?.[0];
      if (!gid || !gno) return alert('Missing data!');
      $.post('<?php echo e(route("colour_prediction.store")); ?>', {
        _token: '<?php echo e(csrf_token()); ?>',
        game_id: gid,
        game_no: gno,
        number: num
      }).done(() => alert('Result submitted!'))
        .fail(() => alert('Submit failed!'));
    });

    // Bootstrap validation fallback
    document.querySelectorAll('.needs-validation').forEach(form => {
      form.addEventListener('submit', e => {
        if (!form.checkValidity()) {
          e.preventDefault();
          form.classList.add('was-validated');
        }
      });
    });
  });
  
  function copyPeriodNo() {
        const periodText = document.getElementById("copy-period").innerText;
        navigator.clipboard.writeText(periodText)
            .then(() => {
                alert("Period No copied: " + periodText);
            })
            .catch(err => {
                alert("Failed to copy: " + err);
            });
    }
    
     setInterval(() => location.reload(), 30000);
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.body.adminmaster', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u853168956/domains/apponrent.com/public_html/trx/resources/views/colour_prediction/index.blade.php ENDPATH**/ ?>