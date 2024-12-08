const express = require('express');
const router = express.Router();
const eventController = require('../controllers/eventController');
const multer = require('multer');
const path = require('path');

// Multer storage configuration
const storage = multer.diskStorage({
    destination: (req, file, cb) => {
        cb(null, '../../assets/eventimages/'); 
    },
    filename: (req, file, cb) => {
        cb(null, Date.now() + path.extname(file.originalname)); 
    },
});

// Multer upload instance
const upload = multer({
    storage: storage,
    limits: { fileSize: 5 * 1024 * 1024 }, 
});


router.get('/status', eventController.getEventsByStatus);

router.put('/updateStatus', eventController.updateEventStatus);

router.get('/schools', eventController.getSchools);

// Get event details by ID
router.get('/details/:id', eventController.getEventDetailsById);

// Update event details
router.put('/update', upload.single('eventImage'), eventController.updateEvent);


router.get('/statistics', eventController.getEventStatistics);



module.exports = router;
