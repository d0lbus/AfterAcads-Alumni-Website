const express = require('express');
const router = express.Router();
const eventController = require('../controllers/eventController');


router.get('/status', eventController.getEventsByStatus);

router.put('/updateStatus', eventController.updateEventStatus);

router.get('/schools', eventController.getSchools);

// Get event details by ID
router.get('/details/:id', eventController.getEventDetailsById);

// Update event details
router.put('/update', eventController.updateEvent);


router.get('/statistics', eventController.getEventStatistics);



module.exports = router;
