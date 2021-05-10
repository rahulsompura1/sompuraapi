<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Order PDF</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  </head>
  <body>
    <h2 class="mb-3">Customer List</h2>
    <table class="table table-bordered">
    <thead>
      <tr>
        <th>Name</th>
        <th>E-mail</th>
        <th>Phone</th>
        <th>DOB</th>
      </tr>
      </thead>
      <tbody>
        @foreach ($data as $row)
        <tr>
            <td>{{ $row->name }}</td>
            <td>{{ $row->email }}</td>
            <td>{{ $row->phone }}</td>
            <td>{{ $row->dob }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </body>
</html>