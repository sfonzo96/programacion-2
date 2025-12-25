<?php

namespace App\Enums;

enum ResponseMessages: string
{
	case OK = "OK";
	case OK_CREATE = "Created successfully.";
	case OK_GET = "Retrieved successfully.";
	case OK_UPDATE = "Updated successfully.";
	case OK_DELETE = "Deleted successfully.";
	case NOT_FOUND = "Resource not found.";
	case UNAUTHORIZED = "Unauthorized access.";
	case FORBIDDEN = "Forbidden access.";
	case FAIL = "Request failed while processing.";
	case NO_RECORDS = "No record was found.";
	case BAD_REQUEST = "Invalid data.";
	case INTERNAL_SERVER_ERROR = "Something went wrong with the request, try again later.";
	case NOT_IMPLEMENTED = "This feature is not implemented yet.";
}
