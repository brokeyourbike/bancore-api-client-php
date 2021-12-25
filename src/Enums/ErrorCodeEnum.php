<?php

// Copyright (C) 2021 Ivan Stasiuk <ivan@stasi.uk>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\Bancore\Enums;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
enum ErrorCodeEnum: string
{
    /**
     * Successful operation
     * remarks: Transaction is successful
     */
    case SUCCESS = '1000';

    /**
     * Request Accepted, Status PENDING
     * remarks: Keep polling the transaction status endpoint until transaction status is successful/failed
     */
    case PENDING = '1001';

    /**
     * Duplicate transaction/session ID
     * remarks: Resend transaction with a unique session/transaction ID
     */
    case DUPLICATE_SESSION_ID = '1002';

    /**
     * Invalid transaction/session ID
     * remarks: Send transaction with a valid session/transaction ID
     */
    case INVALID_SESSION_ID = '1003';

    /**
     * Order and Remit parameters do not match
     * remarks: Retry with valid order and remit parameters
     */
    case ORDER_PARAMS_MISMATCH = '1004';

    /**
     * Expired order
     * remarks: Initiate a new order request and send order reference with remit request
     */
    case ORDER_EXPIRED = '1005';

    /**
     * Failed to process order request
     * remarks: Operations team need to investigate
     */
    case ORDER_FAILED = '1006';

    /**
     * Possible duplicate request
     * remarks: Wait for some minutes and retry transaction
     */
    case POSSIBLE_DUPLICATE_REQUEST = '1007';

    /**
     * Remit failed
     * remarks: Operations team need to investigate
     */
    case REMIT_FAILED = '1008';

    /**
     * Sender daily limit exceeded
     * remarks: Sender's daily limit has been exceeded
     */
    case SENDER_LIMIT_EXCEEDED = '1009';

    /**
     * Recipient daily limit exceeded
     * remarks: Recipient's daily limit has been exceeded
     */
    case RECIPIENT_LIMIT_EXCEEDED = '1010';

    /**
     * Sender minimum amount check failed
     * remarks: Sender's is sending less than the minimum amout configured
     */
    case SENDER_AMOUNT_TOO_SMALL = '1011';

    /**
     * Sender maximum amount check failed
     * remarks: Sender's is sending more than the maximum amout configured
     */
    case SENDER_AMOUNT_TOO_BIG = '1012';

    /**
     * Sender KYC check failed
     * remarks: KYC check failed for the sender
     */
    case SENDER_KYC_FAILED = '1013';

    /**
     * Recipient KYC check failed
     * remarks: KYC check failed for the recipient
     */
    case RECIPIENT_KYC_FAILED = '1014';

    /**
     * Account validation failed
     * remarks: Beneficiary's mobile wallet or bank account validation failed
     */
    case INVALID_ACCOUNT = '1015';

    /**
     * Thirdparty provider not reachable
     * remarks: Wait for some minutes and retry transaction
     */
    case PROVIDER_NOT_REACHABLE = '1016';

    /**
     * Invalid amount
     * remarks: Retry with a valid amount
     */
    case INVALID_AMOUNT = '1017';

    /**
     * Unable to get Forex rate
     * remarks: Operations team need to investigate
     */
    case UNABLE_TO_GET_FOREX_RATE = '1018';

    /**
     * Invalid identifier
     * remarks: Pass the correct identifier in your request
     */
    case INVALID_IDENTIFIER = '1019';

    /**
     * Authentication failure
     * remarks: Authenticate with correct parameters
     */
    case AUTH_FAILED = '1020';

    /**
     * Merchant validation failure
     * remarks: Reauthenticate and pass correct bearer token in your request header
     */
    case MERCHANT_VALIDATION_FAILED = '1021';

    /**
     * Inactive merchant account status
     * remarks: Contact Bancore Switch support team
     */
    case INACTIVE_MERCHANT_STATUS = '1022';

    /**
     * Sender currency not enabled
     * remarks: Contact Bancore Switch support team
     */
    case SENDER_CURRENCY_NOT_ENABLED = '1023';

    /**
     * Missing request parameter(s)
     * remarks: Check your request and send again
     */
    case MISSING_REQUEST_PARAMS = '1024';

    /**
     * Invalid encryption key
     * remarks: Contact Bancore Switch support team
     */
    case INVALID_ENCRYPTION_KEY = '1025';

    /**
     * Insufficient balance
     * remarks: Top up your balance
     */
    case INSUFFICIENT_FUNDS = '1026';

    /**
     * Invalid card number or account number already exist
     * remarks: Retry with valid card details
     */
    case INVALID_CARD_NUMBER = '1027';

    /**
     * Blocked card
     * remarks: Contact Bancore Switch support team
     */
    case BLOCKED_CARD = '1028';

    /**
     * Blocked mobile or bank account number
     * remarks: No further action
     */
    case BLOCKED_MOBILE_OR_BANK_ACCOUNT = '1029';

    /**
     * Wrong PIN
     * remarks: Retry with correct PIN
     */
    case INVALID_PIN = '1030';

    /**
     * Wrong card token
     * remarks: Retry with correct token
     */
    case INVALID_CARD_TOKEN = '1031';

    /**
     * Exceeded failed attempt
     * remarks: Contact Bancore Switch support team
     */
    case FAILED_ATTEMPTS_EXCEEDED = '1032';

    /**
     * Unknown merchant IP address
     * remarks: Send from a permitted IP or contact support
     */
    case INVALID_MERCHANT_IP = '1033';

    /**
     * Fee value not available
     * remarks: Contact Bancore Switch support team
     */
    case INVALID_FEE = '1034';

    /**
     * Merchant daily limit exceeded
     * remarks: Cancel and try the next day
     */
    case MERCHANT_DAILY_LIMIT_EXCEEDED = '1035';

    /**
     * Merchant weekly limit exceeded
     * remarks: Cancel and try the next week
     */
    case MERCHANT_WEEKLY_LIMIT_EXCEEDED = '1036';

    /**
     * Merchant monthly limit exceeded
     * remarks: Cancel and try the next month
     */
    case MERCHANT_MONTHLY_LIMIT_EXCEEDED = '1037';

    /**
     * Order/quote does not exist
     * remarks: Send with valid quote reference
     */
    case QUOTA_NOT_FOUND = '1038';

    /**
     * Quote does not belong to merchant
     * remarks: Send with correct merchant details
     */
    case QUOTA_INVALID = '1039';

    /**
     * Class of service not configured
     * remarks: Contact Bancore Switch support team
     */
    case SERVICE_NOT_CONFIGURED = '1040';

    /**
     * Sender daily velocity/transaction count exceeded
     * remarks: Cancel and try the next day
     */
    case SENDER_DAILY_LIMIT_EXCEEDED = '1041';

    /**
     * No configured wallet for merchant
     * remarks: Contact Bancore Switch support team
     */
    case NO_CONFIGURED_WALLET = '1042';

    /**
     * Beneficiary bank or mobile wallet operator not available
     */
    case RECIPINT_BANK_NOT_AVAILABLE = '1044';

    /**
     * Status unknown. Poll for status
     */
    case UNKNOWN_STATUS = '1053';

    /**
     * Transfer limit exceeded at partner institution.
     */
    case TRANSFER_LIMIT_EXCEEDED_AT_PARTNER_INSTITUTION = '1054';

    /**
     * System malfunction at partner institution
     * remarks: Contact Bancore Switch support team
     */
    case SYSTEM_MALFUNCTION_AT_PARTNER_INSTITUTION = '1055';

    /**
     * Partner institution not available
     * remarks: Contact Bancore Switch support team
     */
    case PARTNER_NOT_AVAILABLE = '1098';

    /**
     * Application error
     * remarks: Wait for some minutes and retry transaction
     */
    case APPLICATION_ERROR = '1099';
}
