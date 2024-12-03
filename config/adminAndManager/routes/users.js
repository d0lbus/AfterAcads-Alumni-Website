const express = require('express');
const router = express.Router();
const userController = require('../controllers/userController');

// Login route
router.post('/login', userController.login);

// Get logged-in user's details
router.get('/getUserDetails', userController.getUserDetails);

module.exports = router;
