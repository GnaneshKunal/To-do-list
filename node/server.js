const express = require('express');
const mongoose = require('mongoose');
const morgan = require('morgan');
const bodyParser = require('body-parser');
const methodOverride = require('method-override');
const favicon = require('serve-favicon');
const db = require('./config/db.json');
const app = express();

mongoose.Promise = global.Promise;
mongoose.connect('mongodb://'+db.user+':'+db.pwd+'@localhost:27017/'+db.database);

//model
var Todo = mongoose.model('Todo',{
    text: String
});
app.use(favicon(__dirname+'/public/ToDo.png'))
.use(express.static(__dirname+'/public'))
.use(morgan('dev'))
.use(bodyParser.json())
.use(bodyParser.urlencoded({extended: false}))
.use(methodOverride());


//routes

app.get('/todos',(req,res)=>{
    Todo.find()
    .exec((err,todos)=>{
        if(err){
            res.status(500).json({ msg:'Bad Request'});
        }else{
            res.status(200).json(todos);
        }
    });
});

app.post('/todos',(req,res)=>{
    to_do = new Todo({
        text:req.body.text
    });
    to_do.save((err)=>{
        if(err){
            res.status(500).json({msg:'Bad Request'});
        }else{
            Todo.find().exec((err,todos)=>{
                if(err){
                    res.status(500).json({msg:'Bad Request'});
                }else{
                    res.status(200).json(todos);
                }
            });
        }
    });
});


app.delete('/todos/:todo_id',(req,res)=>{
    Todo.remove({
        _id:req.params.todo_id
    }).exec((err,todo)=>{
        if(err){
            res.status(500).json({msg:'Bad Request'});
        }else{
            Todo.find().exec((err,todos)=>{
                if(err){
                    err.status(500).json({msg:'Bad Request'});
                }else{
                    res.status(200).json(todos);
                }
            });
        }
    });
});
app.get('*',(req,res)=>{
    res.sendFile('./public/index.html');
})
app.listen(8080,()=>{
    console.log('App listening on port 8080');
});