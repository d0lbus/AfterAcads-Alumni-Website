const express = require('express');
const router = express.Router();
const userController = require('../controllers/userController');
const userStatisticsController = require('../controllers/userStatisticsController');
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

// Get total number of approved users
router.get('/usercount', userStatisticsController.getUserStatistics);

// Route to get counts of users by employment status
router.get('/employment-status', userStatisticsController.getEmploymentStatusCounts);

// Route to get counts of users by gender
router.get('/gendergroup', userStatisticsController.getGenderCounts);

// Route to get counts of users grouped by batch_id (excluding id 1)
router.get('/batchesgroup', userStatisticsController.getUsersGroupedByBatch);

// Route to get counts of users grouped by school_id (excluding id 1)
router.get('/schoolsgroup', userStatisticsController.getUsersGroupedBySchool);

// Route to get counts of users grouped by course_id (excluding id 1)
router.get('/coursesgroup', userStatisticsController.getUsersGroupedByCourse);


module.exports = router;
