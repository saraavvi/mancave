<div class="row d-flex justify-content-center">
    <div class="col-md-10">
        <table class="table">
            <thead>
                <tr>
                <?php
                foreach ($column_name_array as $column_name) {
                    echo "<th scope='col'>$column_name</th>";
                }
                ?>
                </tr>
            </thead>
        <tbody>