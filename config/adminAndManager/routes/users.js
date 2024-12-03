const express = require('express');
const router = express.Router();
const userController = require('../controllers/userController');

// Login route
router.post('/login', userController.login);

// Get logged-in user's details
router.get('/details', userController.getUserDetails);

module.exports = router;
