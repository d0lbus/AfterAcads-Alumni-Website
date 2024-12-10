const express = require('express');
const router = express.Router();
const userController = require('../controllers/userController');
const authMiddleware = require('../middlewares/authMiddleware');

// Apply authMiddleware to all routes in this router except for login
router.use(authMiddleware);

// Login route
router.post('/login', userController.login);

// Logout route
router.post('/logout', (req, res) => {
    if (!req.session) {
        return res.status(200).json({ message: 'No active session to log out.' });
    }

    req.session.destroy((err) => {
        if (err) {
            return res.status(500).json({ error: 'Failed to log out.' });
        }
        res.clearCookie('connect.sid');
        res.json({ message: 'Logged out successfully' });
    });
});

// Get logged-in user's details
router.get('/details', userController.getUserDetails);

// Get User Details By Email
router.get('/registrantdetails/:email', userController.getUserDetailsByEmail);

// Get Users and Group Them By Status
router.get('/status', userController.getUsersByStatus);

// Get Approved Users Only
router.get('/getApprovedUsers',userController.getApprovedUsers);

//
router.get('/getUserById/:id', userController.getUserById);


router.get('/getBatches', userController.getBatches);
  

router.post('/updateUser', userController.updateUser);

// Update User Status
router.post('/updateStatus', userController.updateUserStatus);




module.exports = router;
