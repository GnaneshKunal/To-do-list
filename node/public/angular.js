var Todo = angular.module('Todo',[]);
Todo.controller('mainController',['$scope','$http',($scope,$http)=>{
    $scope.formData = {};
    $http.get('/todos').then((data)=>{
        $scope.todos = data.data;
    },(data)=>{
        console.log(`Error: ${data}`);
    });

    $scope.createTodo = function(){
        $http.post('/todos',$scope.formData)
        .then((data)=>{
            $scope.formData = {};
            $scope.todos = data.data;
        },(data)=>{
            console.log(`Error: ${data}`);
        });
    };

    $scope.deleteTodo = function(id){
        $http.delete('/todos/'+id).then((data)=>{
            $scope.todos = data.data;
        },(data)=>{
            console.log(`Error: ${data}`);
        });
    };
}])