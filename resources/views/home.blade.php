@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<!-- fichier: mindmap.html -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>FocusMap - Objectifs</title>
  <style>
    .goal-box {
      margin: 20px;
      padding: 10px;
      border: 2px solid #999;
      display: inline-block;
      width: 300px;
    }

    .objective {
      margin-left: 20px;
      margin-top: 5px;
    }

    .progress-container {
      background-color: #ddd;
      width: 100%;
      height: 12px;
      border-radius: 6px;
      overflow: hidden;
      margin-bottom: 10px;
    }

    .progress-bar {
      height: 100%;
      background: green;
      width: 0%;
      transition: width 0.3s ease;
    }
  </style>
</head>
<body>

<h2>Nouvel Objectif</h2>
<form action="save_goal.php" method="POST" id="goalForm">
  <label>Goal:</label>
  <input type="text" name="goal_title" required><br><br>

  <div class="goal-box">
    <div class="progress-container">
      <div class="progress-bar" id="progressBar"></div>
    </div>

    <label>Goal 1</label>
    <input type="checkbox" name="goal_checked"><br>

    <div class="objective">
      <label>Objective 1:</label>
      <input type="text" name="objectives[]" class="track">
    </div>

    <div class="objective">
      <label>Objective 2:</label>
      <input type="text" name="objectives[]" class="track">
    </div>

    <div class="objective">
      <label>Objective 3:</label>
      <input type="text" name="objectives[]" class="track">
    </div>

    <div class="objective">
      <label>Objective 4:</label>
      <input type="text" name="objectives[]" class="track">
    </div>
  </div>

  <br><input type="submit" value="Sauvegarder">
</form>

<script>
  const inputs = document.querySelectorAll('.track');
  const goalInput = document.querySelector('input[name="goal_title"]');
  const progressBar = document.getElementById('progressBar');

  function updateProgress() {
    let total = inputs.length + 1; // 4 objectives + 1 goal title
    let filled = 0;

    if (goalInput.value.trim() !== '') filled++;

    inputs.forEach(input => {
      if (input.value.trim() !== '') filled++;
    });

    let percent = (filled / total) * 100;
    progressBar.style.width = percent + "%";
  }

  // Listen for typing in all tracked fields
  [...inputs, goalInput].forEach(input => {
    input.addEventListener('input', updateProgress);
  });

  // Init on page load
  updateProgress();
</script>

</body>
</html>
