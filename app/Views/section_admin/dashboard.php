<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('admin_title') ?>
Dashboard Admin
<?= $this->endSection() ?>

<?= $this->section('admin_content') ?>
<div class="container">
  <!-- <div>
    <h4 class="mb-4">{page_title} Statistics</h4>

    {userStatistics}
    <ul>
      <li>Total Users : <span class="fw-bold">{total_users}</span></li>
      <li>Active Users : <span class="fw-bold">{active_users}</span></li>
      <li>New User This Month : <span class="fw-bold">{new_users_this_month}</span></li>
      <li>Monthly Grow Percentage : <span class="fw-bold">{growth_percentage}%</span></li>
    </ul>
    {/userStatistics}

    {!product_statistics_cell!}

  </div> -->
  <div class="row">

    <div class="col-md-6">
      <div class="card">
        <div class="card-body">
          <div class="chart-container">
            <canvas id="pieChart" height="200"></canvas>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card">
        <div class="card-body">
          <div class="chart-container">
            <canvas id="mostProductChart" height="200"></canvas>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-12 mt-4">
      <div class="card">
        <div class="card-body">
          <div class="chart-container">
            <canvas id="productGrowthChart" height="200"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>


</div>
<?= $this->endSection() ?>

<?= $this->section('admin_scripts') ?>

<script>
  const productByCategory = <?= $productByCategory ?>;
  const mostProductByCategory = <?= $mostProductByCategory ?>;
  const productGrowthData = <?= $productGrowth ?>;


  const pieChart = new Chart(
    document.getElementById('pieChart'), {
      type: 'pie',
      data: productByCategory,
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          title: {
            display: true,
            text: 'Product by Category'
          },
          legend: {
            position: 'right'
          }
        }
      }
    }
  );

  const mostProductChart = new Chart(

    document.getElementById('mostProductChart'), {
      type: 'bar',
      data: mostProductByCategory,
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true,
            title: {
              display: true,
              text: 'Total Products'
            }
          },
          x: {
            title: {
              display: true,
              text: 'Name'
            }
          }
        },
        plugins: {
          title: {
            display: true,
            text: 'Top 5 Categories with Most Products'
          }
        }
      }
    }
  );

  const productGrowthChart = new Chart(
    document.getElementById('productGrowthChart'), {
      type: 'line',
      data: productGrowthData,
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            min: 0,
            max: 4,
            title: {
              display: true,
              text: 'New Product(s) Count'
            }
          },
          x: {
            title: {
              display: true,
              text: 'Month'
            }
          }
        },
        plugins: {
          title: {
            display: true,
            text: 'Product Growth (per Month) for <?= $currentYear; ?>'
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                return `New Product(s) Count: ${context.raw}`;
              }
            }
          }
        }
      }
    }
  );
</script>

<?= $this->endSection() ?>